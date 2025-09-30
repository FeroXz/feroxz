import { Link } from 'react-router-dom';

export function NotFoundPage() {
  return (
    <section className="container" style={{ display: 'grid', placeItems: 'center', minHeight: '60vh' }}>
      <div className="glass-panel light" style={{ display: 'grid', gap: '1rem', maxWidth: '420px', textAlign: 'center' }}>
        <span className="tag">404</span>
        <h1 style={{ margin: 0 }}>Seite nicht gefunden</h1>
        <p style={{ color: 'var(--text-muted)' }}>
          Die gewünschte Seite konnte nicht geladen werden. Zurück zur Übersicht, um weiter zu stöbern.
        </p>
        <Link to="/" style={{
          borderRadius: '999px',
          padding: '0.75rem 1.5rem',
          background: 'linear-gradient(135deg, #5be584, #1ec07d)',
          color: '#04110b',
          fontWeight: 600
        }}>
          Zur Startseite
        </Link>
      </div>
    </section>
  );
}
