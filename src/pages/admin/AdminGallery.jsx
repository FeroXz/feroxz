import { useMemo, useState } from 'react';
import { useData } from '../../context/DataContext.jsx';

const emptyForm = {
  title: '',
  description: '',
  imageUrl: '',
  createdAt: new Date().toISOString().slice(0, 16)
};

export default function AdminGallery() {
  const { galleryItems, createGalleryItem, updateGalleryItem, deleteGalleryItem } = useData();
  const [form, setForm] = useState(emptyForm);
  const [editingId, setEditingId] = useState(null);

  const sortedItems = useMemo(
    () => [...galleryItems].sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)),
    [galleryItems]
  );

  const handleSubmit = (event) => {
    event.preventDefault();
    const payload = {
      title: form.title,
      description: form.description,
      imageUrl: form.imageUrl,
      createdAt: form.createdAt ? new Date(form.createdAt).toISOString() : undefined
    };

    if (editingId) {
      updateGalleryItem(editingId, payload);
    } else {
      createGalleryItem(payload);
    }

    setForm({ ...emptyForm, createdAt: new Date().toISOString().slice(0, 16) });
    setEditingId(null);
  };

  const handleEdit = (item) => {
    setEditingId(item.id);
    setForm({
      title: item.title,
      description: item.description,
      imageUrl: item.imageUrl,
      createdAt: item.createdAt ? item.createdAt.slice(0, 16) : new Date().toISOString().slice(0, 16)
    });
  };

  const handleDelete = (id) => {
    if (window.confirm('Eintrag wirklich löschen?')) {
      deleteGalleryItem(id);
    }
  };

  const handleFile = async (file) => {
    if (!file) {
      return;
    }

    const dataUrl = await readFileAsDataUrl(file);
    setForm((prev) => ({ ...prev, imageUrl: dataUrl }));
  };

  return (
    <div className="admin-section">
      <header>
        <h2>Galerie</h2>
        <p>Verwalte Bilder mit URL oder lade lokale Dateien (werden als Data-URL gespeichert).</p>
      </header>
      <form className="form-grid" onSubmit={handleSubmit}>
        <label>
          Titel
          <input value={form.title} onChange={(event) => setForm((prev) => ({ ...prev, title: event.target.value }))} required />
        </label>
        <label>
          Beschreibung
          <textarea
            value={form.description}
            onChange={(event) => setForm((prev) => ({ ...prev, description: event.target.value }))}
            rows={5}
          />
        </label>
        <label>
          Bild-URL
          <input
            value={form.imageUrl}
            onChange={(event) => setForm((prev) => ({ ...prev, imageUrl: event.target.value }))}
            placeholder="https://…"
          />
        </label>
        <label>
          Datei hochladen
          <input type="file" accept="image/*" onChange={(event) => handleFile(event.target.files?.[0])} />
        </label>
        <label>
          Erstellt am
          <input
            type="datetime-local"
            value={form.createdAt}
            onChange={(event) => setForm((prev) => ({ ...prev, createdAt: event.target.value }))}
          />
        </label>
        <button type="submit" className="button">
          {editingId ? 'Galerie-Eintrag aktualisieren' : 'Galerie-Eintrag anlegen'}
        </button>
      </form>

      <div className="list">
        {sortedItems.map((item) => (
          <article key={item.id} className="list-item">
            <h3>{item.title}</h3>
            <p>{item.description}</p>
            <div className="list-item__meta">
              <span>{new Date(item.createdAt).toLocaleString('de-DE')}</span>
              <span>{item.imageUrl.startsWith('data:') ? 'Data-URL' : item.imageUrl}</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
              <button type="button" className="button button--ghost" onClick={() => handleEdit(item)}>
                Bearbeiten
              </button>
              <button type="button" className="button button--danger" onClick={() => handleDelete(item.id)}>
                Löschen
              </button>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}

function readFileAsDataUrl(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.addEventListener('load', () => resolve(reader.result));
    reader.addEventListener('error', () => reject(reader.error));
    reader.readAsDataURL(file);
  });
}
