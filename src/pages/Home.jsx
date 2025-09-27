import { Link } from 'react-router-dom';
import { useMemo } from 'react';
import { useData } from '../context/DataContext.jsx';

export default function Home() {
  const { posts, pages } = useData();

  const sortedPosts = useMemo(
    () => [...posts].sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt)),
    [posts]
  );

  return (
    <div className="home">
      <section className="hero">
        <span className="tag">React Version</span>
        <h1>Feroxz Mini CMS</h1>
        <p>
          Verwalte Inhalte, Galerie und Genetik-Profile direkt im Browser. Alle Daten werden im lokalen Speicher gesichert –
          ideal für Präsentationen oder schnelle Prototypen.
        </p>
        <div className="hero-actions">
          <Link className="button" to="/admin/login">
            Zum Adminbereich
          </Link>
          <Link className="button button--ghost" to="/genetics">
            Genetik entdecken
          </Link>
        </div>
      </section>

      <section className="section-title">
        <h2>Aktuelle Beiträge</h2>
        <p>Neuigkeiten aus Zucht, Pflege und Projekten.</p>
      </section>
      <div className="card-grid">
        {sortedPosts.map((post) => (
          <article key={post.id} className="card">
            <h3>{post.title}</h3>
            <p>{post.excerpt}</p>
            <div className="list-item__meta">
              <span className="badge">{new Date(post.publishedAt).toLocaleDateString('de-DE')}</span>
            </div>
            <details>
              <summary>Weiterlesen</summary>
              <p>{post.content}</p>
            </details>
          </article>
        ))}
      </div>

      <section className="section-title" style={{ marginTop: '3rem' }}>
        <h2>Statische Seiten</h2>
        <p>Zusätzliche Informationen aus dem CMS.</p>
      </section>
      <div className="card-grid">
        {pages.map((page) => (
          <article key={page.id} className="card">
            <h3>{page.title}</h3>
            <p>{page.content.slice(0, 180)}{page.content.length > 180 ? '…' : ''}</p>
            <Link className="button button--ghost" to={`/page/${page.slug}`}>
              Seite ansehen
            </Link>
          </article>
        ))}
      </div>
    </div>
  );
}
