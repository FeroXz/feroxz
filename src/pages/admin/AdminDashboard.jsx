import { Link } from 'react-router-dom';
import { useData } from '../../context/DataContext.jsx';

export default function AdminDashboard() {
  const { posts, pages, galleryItems, species, resetData } = useData();

  return (
    <div className="admin-section">
      <header>
        <h2>Willkommen im Dashboard</h2>
        <p>Verwalte Inhalte und sieh dir den aktuellen Status deines Mini CMS an.</p>
      </header>
      <div className="card-grid">
        <article className="card">
          <h3>Beiträge</h3>
          <p>{posts.length} veröffentlicht</p>
          <Link className="button button--ghost" to="/admin/posts">
            Verwalten
          </Link>
        </article>
        <article className="card">
          <h3>Seiten</h3>
          <p>{pages.length} aktiv</p>
          <Link className="button button--ghost" to="/admin/pages">
            Verwalten
          </Link>
        </article>
        <article className="card">
          <h3>Galerie</h3>
          <p>{galleryItems.length} Einträge</p>
          <Link className="button button--ghost" to="/admin/gallery">
            Verwalten
          </Link>
        </article>
        <article className="card">
          <h3>Genetik</h3>
          <p>{species.reduce((total, entry) => total + entry.genes.length, 0)} Gene in {species.length} Arten</p>
          <Link className="button button--ghost" to="/admin/genetics">
            Verwalten
          </Link>
        </article>
      </div>
      <div className="list-item" style={{ marginTop: '1.5rem' }}>
        <h3>Zurücksetzen</h3>
        <p>
          Falls du komplett neu starten möchtest, kannst du die Standarddaten laden. Alle lokal gespeicherten Änderungen gehen
          verloren.
        </p>
        <button type="button" className="button button--danger" onClick={resetData}>
          Daten zurücksetzen
        </button>
      </div>
    </div>
  );
}
