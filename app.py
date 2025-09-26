import os
import sqlite3
from datetime import datetime
from functools import wraps

from flask import (
    Flask,
    g,
    redirect,
    render_template,
    request,
    session,
    url_for,
    flash,
    jsonify,
)
from werkzeug.security import check_password_hash, generate_password_hash
from werkzeug.utils import secure_filename


DATABASE = os.path.join(os.path.dirname(__file__), "cms.db")
UPLOAD_FOLDER = os.path.join(os.path.dirname(__file__), "static", "uploads")
ALLOWED_IMAGE_EXTENSIONS = {"png", "jpg", "jpeg", "gif", "webp"}
DEFAULT_ADMIN = {
    "username": os.environ.get("CMS_ADMIN_USERNAME", "admin"),
    "password": os.environ.get("CMS_ADMIN_PASSWORD", "changeme"),
}


app = Flask(__name__)
app.config["SECRET_KEY"] = os.environ.get("CMS_SECRET", "replace-me")
app.config["UPLOAD_FOLDER"] = UPLOAD_FOLDER
app.config["MAX_CONTENT_LENGTH"] = 8 * 1024 * 1024  # 8 MB

os.makedirs(app.config["UPLOAD_FOLDER"], exist_ok=True)


@app.context_processor
def inject_globals():
    return {"current_year": datetime.utcnow().year}


def get_db():
    if "db" not in g:
        g.db = sqlite3.connect(DATABASE)
        g.db.row_factory = sqlite3.Row
    return g.db


@app.teardown_appcontext
def close_db(error):
    db = g.pop("db", None)
    if db is not None:
        db.close()


def init_db():
    db = get_db()
    db.execute(
        """
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )
        """
    )
    db.execute(
        """
        CREATE TABLE IF NOT EXISTS gallery_images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            description TEXT,
            image_path TEXT NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )
        """
    )
    db.execute(
        """
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL
        )
        """
    )
    db.commit()

    cur = db.execute("SELECT id FROM admins WHERE username = ?", (DEFAULT_ADMIN["username"],))
    if cur.fetchone() is None:
        db.execute(
            "INSERT INTO admins (username, password_hash) VALUES (?, ?)",
            (
                DEFAULT_ADMIN["username"],
                generate_password_hash(DEFAULT_ADMIN["password"]),
            ),
        )
        db.commit()


@app.before_request
def before_request():
    init_db()


def login_required(view):
    @wraps(view)
    def wrapped_view(**kwargs):
        if not session.get("admin_id"):
            flash("Bitte melde dich zuerst an.", "warning")
            return redirect(url_for("admin_login", next=request.path))
        return view(**kwargs)

    return wrapped_view


@app.route("/")
def index():
    posts = get_db().execute(
        "SELECT id, title, content, created_at, updated_at FROM posts ORDER BY created_at DESC"
    ).fetchall()
    gallery = get_db().execute(
        "SELECT id, title, description, image_path FROM gallery_images ORDER BY created_at DESC"
    ).fetchall()
    return render_template("index.html", posts=posts, gallery=gallery)


@app.route("/admin/login", methods=["GET", "POST"])
def admin_login():
    if request.method == "POST":
        username = request.form.get("username", "").strip()
        password = request.form.get("password", "")
        db = get_db()
        user = db.execute(
            "SELECT id, username, password_hash FROM admins WHERE username = ?",
            (username,),
        ).fetchone()

        if user and check_password_hash(user["password_hash"], password):
            session["admin_id"] = user["id"]
            session["admin_username"] = user["username"]
            flash("Willkommen zurück!", "success")
            return redirect(request.args.get("next") or url_for("admin_dashboard"))

        flash("Ungültige Zugangsdaten.", "danger")

    return render_template("admin/login.html")


@app.route("/admin/logout")
def admin_logout():
    session.clear()
    flash("Du wurdest abgemeldet.", "info")
    return redirect(url_for("index"))


@app.route("/admin")
@login_required
def admin_dashboard():
    posts = get_db().execute(
        "SELECT id, title, created_at, updated_at FROM posts ORDER BY created_at DESC"
    ).fetchall()
    return render_template("admin/dashboard.html", posts=posts)


@app.route("/admin/post/new", methods=["GET", "POST"])
@login_required
def admin_create_post():
    if request.method == "POST":
        title = request.form.get("title", "").strip()
        content = request.form.get("content", "").strip()
        if not title or not content:
            flash("Titel und Inhalt dürfen nicht leer sein.", "warning")
        else:
            now = datetime.utcnow().isoformat()
            db = get_db()
            db.execute(
                "INSERT INTO posts (title, content, created_at, updated_at) VALUES (?, ?, ?, ?)",
                (title, content, now, now),
            )
            db.commit()
            flash("Beitrag wurde erstellt.", "success")
            return redirect(url_for("admin_dashboard"))

    return render_template("admin/edit_post.html", post=None)


@app.route("/admin/post/<int:post_id>/edit", methods=["GET", "POST"])
@login_required
def admin_edit_post(post_id):
    db = get_db()
    post = db.execute(
        "SELECT id, title, content FROM posts WHERE id = ?",
        (post_id,),
    ).fetchone()

    if post is None:
        flash("Beitrag wurde nicht gefunden.", "danger")
        return redirect(url_for("admin_dashboard"))

    if request.method == "POST":
        title = request.form.get("title", "").strip()
        content = request.form.get("content", "").strip()
        if not title or not content:
            flash("Titel und Inhalt dürfen nicht leer sein.", "warning")
        else:
            db.execute(
                "UPDATE posts SET title = ?, content = ?, updated_at = ? WHERE id = ?",
                (title, content, datetime.utcnow().isoformat(), post_id),
            )
            db.commit()
            flash("Beitrag wurde aktualisiert.", "success")
            return redirect(url_for("admin_dashboard"))

    return render_template("admin/edit_post.html", post=post)


@app.route("/admin/post/<int:post_id>/delete", methods=["POST"])
@login_required
def admin_delete_post(post_id):
    db = get_db()
    db.execute("DELETE FROM posts WHERE id = ?", (post_id,))
    db.commit()
    flash("Beitrag wurde gelöscht.", "info")
    return redirect(url_for("admin_dashboard"))


def allowed_file(filename: str) -> bool:
    return "." in filename and filename.rsplit(".", 1)[1].lower() in ALLOWED_IMAGE_EXTENSIONS


def save_image(file_storage) -> str:
    filename = secure_filename(file_storage.filename)
    name, ext = os.path.splitext(filename)
    timestamp = datetime.utcnow().strftime("%Y%m%d%H%M%S%f")
    filename = f"{name}_{timestamp}{ext}"
    file_path = os.path.join(app.config["UPLOAD_FOLDER"], filename)
    file_storage.save(file_path)
    return os.path.join("uploads", filename)


@app.route("/admin/uploads/post-image", methods=["POST"])
@login_required
def admin_upload_post_image():
    file = request.files.get("file")
    if file is None or file.filename == "":
        return jsonify({"error": "Keine Datei hochgeladen."}), 400
    if not allowed_file(file.filename):
        return jsonify({"error": "Ungültiges Dateiformat."}), 400

    relative_path = save_image(file)
    return jsonify(
        {
            "location": url_for("static", filename=relative_path, _external=True),
        }
    )


@app.route("/admin/gallery")
@login_required
def admin_gallery():
    images = get_db().execute(
        "SELECT id, title, description, image_path, created_at, updated_at FROM gallery_images ORDER BY created_at DESC"
    ).fetchall()
    return render_template("admin/gallery.html", images=images)


@app.route("/admin/gallery/new", methods=["GET", "POST"])
@login_required
def admin_gallery_new():
    if request.method == "POST":
        title = request.form.get("title", "").strip()
        description = request.form.get("description", "").strip()
        file = request.files.get("image")

        if file is None or file.filename == "":
            flash("Bitte wähle eine Bilddatei aus.", "warning")
        elif not allowed_file(file.filename):
            flash("Das Dateiformat wird nicht unterstützt.", "danger")
        else:
            relative_path = save_image(file)
            now = datetime.utcnow().isoformat()
            db = get_db()
            db.execute(
                """
                INSERT INTO gallery_images (title, description, image_path, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?)
                """,
                (title or None, description or None, relative_path, now, now),
            )
            db.commit()
            flash("Bild wurde hinzugefügt.", "success")
            return redirect(url_for("admin_gallery"))

    return render_template("admin/edit_gallery_image.html", image=None)


@app.route("/admin/gallery/<int:image_id>/edit", methods=["GET", "POST"])
@login_required
def admin_gallery_edit(image_id):
    db = get_db()
    image = db.execute(
        "SELECT id, title, description, image_path FROM gallery_images WHERE id = ?",
        (image_id,),
    ).fetchone()

    if image is None:
        flash("Bild wurde nicht gefunden.", "danger")
        return redirect(url_for("admin_gallery"))

    if request.method == "POST":
        title = request.form.get("title", "").strip()
        description = request.form.get("description", "").strip()
        file = request.files.get("image")
        new_path = image["image_path"]

        if file and file.filename:
            if not allowed_file(file.filename):
                flash("Das Dateiformat wird nicht unterstützt.", "danger")
                return render_template("admin/edit_gallery_image.html", image=image)

            new_path = save_image(file)
            old_file = os.path.join(app.static_folder, image["image_path"])
            if os.path.exists(old_file):
                os.remove(old_file)

        db.execute(
            """
            UPDATE gallery_images
            SET title = ?, description = ?, image_path = ?, updated_at = ?
            WHERE id = ?
            """,
            (title or None, description or None, new_path, datetime.utcnow().isoformat(), image_id),
        )
        db.commit()
        flash("Bild wurde aktualisiert.", "success")
        return redirect(url_for("admin_gallery"))

    return render_template("admin/edit_gallery_image.html", image=image)


@app.route("/admin/gallery/<int:image_id>/delete", methods=["POST"])
@login_required
def admin_gallery_delete(image_id):
    db = get_db()
    image = db.execute(
        "SELECT image_path FROM gallery_images WHERE id = ?",
        (image_id,),
    ).fetchone()

    if image:
        file_path = os.path.join(app.static_folder, image["image_path"])
        if os.path.exists(file_path):
            os.remove(file_path)
        db.execute("DELETE FROM gallery_images WHERE id = ?", (image_id,))
        db.commit()
        flash("Bild wurde gelöscht.", "info")
    else:
        flash("Bild wurde nicht gefunden.", "warning")

    return redirect(url_for("admin_gallery"))


if __name__ == "__main__":
    app.run(debug=True)
