import { useMemo, useState } from 'react';
import { useData } from '../../context/DataContext.jsx';

const emptyForm = {
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  publishedAt: new Date().toISOString().slice(0, 16)
};

export default function AdminPosts() {
  const { posts, createPost, updatePost, deletePost } = useData();
  const [form, setForm] = useState(emptyForm);
  const [editingId, setEditingId] = useState(null);

  const sortedPosts = useMemo(
    () => [...posts].sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt)),
    [posts]
  );

  const handleSubmit = (event) => {
    event.preventDefault();
    const payload = {
      title: form.title,
      slug: form.slug,
      excerpt: form.excerpt,
      content: form.content,
      publishedAt: form.publishedAt ? new Date(form.publishedAt).toISOString() : undefined
    };

    if (editingId) {
      updatePost(editingId, payload);
    } else {
      createPost(payload);
    }

    setForm({ ...emptyForm, publishedAt: new Date().toISOString().slice(0, 16) });
    setEditingId(null);
  };

  const handleEdit = (post) => {
    setEditingId(post.id);
    setForm({
      title: post.title,
      slug: post.slug,
      excerpt: post.excerpt,
      content: post.content,
      publishedAt: post.publishedAt ? post.publishedAt.slice(0, 16) : new Date().toISOString().slice(0, 16)
    });
  };

  const handleDelete = (id) => {
    if (window.confirm('Eintrag wirklich löschen?')) {
      deletePost(id);
    }
  };

  return (
    <div className="admin-section">
      <header>
        <h2>Beiträge</h2>
        <p>Erstelle und bearbeite Blog-Beiträge für die Startseite.</p>
      </header>
      <form className="form-grid" onSubmit={handleSubmit}>
        <label>
          Titel
          <input value={form.title} onChange={(event) => setForm((prev) => ({ ...prev, title: event.target.value }))} required />
        </label>
        <label>
          Slug (optional)
          <input value={form.slug} onChange={(event) => setForm((prev) => ({ ...prev, slug: event.target.value }))} />
        </label>
        <label>
          Kurzbeschreibung
          <textarea
            value={form.excerpt}
            onChange={(event) => setForm((prev) => ({ ...prev, excerpt: event.target.value }))}
            maxLength={280}
          />
        </label>
        <label>
          Inhalt
          <textarea
            value={form.content}
            onChange={(event) => setForm((prev) => ({ ...prev, content: event.target.value }))}
            required
            rows={8}
          />
        </label>
        <label>
          Veröffentlichungsdatum
          <input
            type="datetime-local"
            value={form.publishedAt}
            onChange={(event) => setForm((prev) => ({ ...prev, publishedAt: event.target.value }))}
          />
        </label>
        <button type="submit" className="button">
          {editingId ? 'Beitrag aktualisieren' : 'Beitrag anlegen'}
        </button>
      </form>

      <div className="list">
        {sortedPosts.map((post) => (
          <article key={post.id} className="list-item">
            <h3>{post.title}</h3>
            <p>{post.excerpt}</p>
            <div className="list-item__meta">
              <span>Slug: {post.slug}</span>
              <span>Veröffentlicht: {new Date(post.publishedAt).toLocaleString('de-DE')}</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
              <button type="button" className="button button--ghost" onClick={() => handleEdit(post)}>
                Bearbeiten
              </button>
              <button type="button" className="button button--danger" onClick={() => handleDelete(post.id)}>
                Löschen
              </button>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}
