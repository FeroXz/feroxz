import { useMemo, useState } from 'react';
import { useData } from '../../context/DataContext.jsx';

const emptyForm = {
  title: '',
  slug: '',
  content: ''
};

export default function AdminPages() {
  const { pages, createPage, updatePage, deletePage } = useData();
  const [form, setForm] = useState(emptyForm);
  const [editingId, setEditingId] = useState(null);

  const sortedPages = useMemo(() => [...pages].sort((a, b) => a.title.localeCompare(b.title)), [pages]);

  const handleSubmit = (event) => {
    event.preventDefault();
    const payload = {
      title: form.title,
      slug: form.slug,
      content: form.content
    };

    if (editingId) {
      updatePage(editingId, payload);
    } else {
      createPage(payload);
    }

    setForm(emptyForm);
    setEditingId(null);
  };

  const handleEdit = (page) => {
    setEditingId(page.id);
    setForm({ title: page.title, slug: page.slug, content: page.content });
  };

  const handleDelete = (id) => {
    if (window.confirm('Seite wirklich löschen?')) {
      deletePage(id);
    }
  };

  return (
    <div className="admin-section">
      <header>
        <h2>Seiten</h2>
        <p>Pflege statische Inhalte wie Über-uns oder Pflegetipps.</p>
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
          Inhalt
          <textarea
            value={form.content}
            onChange={(event) => setForm((prev) => ({ ...prev, content: event.target.value }))}
            rows={8}
            required
          />
        </label>
        <button type="submit" className="button">
          {editingId ? 'Seite aktualisieren' : 'Seite anlegen'}
        </button>
      </form>

      <div className="list">
        {sortedPages.map((page) => (
          <article key={page.id} className="list-item">
            <h3>{page.title}</h3>
            <p>{page.content.slice(0, 160)}{page.content.length > 160 ? '…' : ''}</p>
            <div className="list-item__meta">
              <span>Slug: {page.slug}</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
              <button type="button" className="button button--ghost" onClick={() => handleEdit(page)}>
                Bearbeiten
              </button>
              <button type="button" className="button button--danger" onClick={() => handleDelete(page.id)}>
                Löschen
              </button>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}
