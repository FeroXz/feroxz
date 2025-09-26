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
)
from werkzeug.security import check_password_hash, generate_password_hash


DATABASE = os.path.join(os.path.dirname(__file__), "cms.db")
DEFAULT_ADMIN = {
    "username": os.environ.get("CMS_ADMIN_USERNAME", "admin"),
    "password": os.environ.get("CMS_ADMIN_PASSWORD", "changeme"),
}


app = Flask(__name__)
app.config["SECRET_KEY"] = os.environ.get("CMS_SECRET", "replace-me")


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
    return render_template("index.html", posts=posts)


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


if __name__ == "__main__":
    app.run(debug=True)
