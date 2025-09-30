import { useAppData } from '../context/AppDataContext.jsx';
import { Link } from 'react-router-dom';

export function HomePage() {
  const {
    state: { settings, animals }
  } = useAppData();

  const showcased = animals.filter((animal) => animal.showcase);

  return (
    <section className="container" style={{ display: 'grid', gap: '2.5rem' }}>
      <div className="glass-panel light" style={{ display: 'grid', gap: '1.5rem' }}>
        <span className="tag">Reptile Experience</span>
        <h1 style={{ fontSize: 'clamp(2.5rem, 4vw, 3.5rem)', margin: 0 }}>{settings.heroTitle}</h1>
        <p style={{ color: 'var(--text-muted)', fontSize: '1.05rem', lineHeight: 1.7 }}>{settings.heroIntro}</p>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '1rem' }}>
          <PrimaryLink to="/genetics">Genetik kalkulieren</PrimaryLink>
          <SecondaryLink to="/care-guides">Pflegeleitfäden erkunden</SecondaryLink>
        </div>
      </div>

      <div className="grid two">
        <div className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
          <span className="tag">Pflege</span>
          <h2 style={{ margin: 0 }}>Praxisnahe Guides</h2>
          <p style={{ color: 'var(--text-muted)' }}>
            Detaillierte Haltungsprofile für Pogona vitticeps und Heterodon nasicus mit Temperaturtabellen,
            Fütterungsfrequenzen und Gesundheitsvorsorge.
          </p>
          <SecondaryLink to="/care-guides">Zu den Leitfäden</SecondaryLink>
        </div>
        <div className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
          <span className="tag">Verwaltung</span>
          <h2 style={{ margin: 0 }}>Deine Tiere im Blick</h2>
          <p style={{ color: 'var(--text-muted)' }}>
            Dokumentiere Genetik, Herkunft, Besonderheiten und Bilder deiner Tiere. Nutze die Daten direkt im
            Rechner.
          </p>
          <SecondaryLink to="/animals">Tierübersicht ansehen</SecondaryLink>
        </div>
      </div>

      <div className="glass-panel" style={{ display: 'grid', gap: '1.5rem' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '1rem' }}>
          <div>
            <span className="tag">Showcase</span>
            <h2 style={{ margin: '0.5rem 0 0' }}>Kuratiertes Highlight</h2>
          </div>
          <SecondaryLink to="/admin">Showcase verwalten</SecondaryLink>
        </div>
        <div className="grid two">
          {showcased.map((animal) => (
            <article key={animal.id} className="glass-panel light" style={{ padding: 0, overflow: 'hidden' }}>
              <div style={{ aspectRatio: '4 / 3', backgroundImage: `url(${animal.image})`, backgroundSize: 'cover', backgroundPosition: 'center' }} />
              <div style={{ padding: '1.25rem', display: 'grid', gap: '0.65rem' }}>
                <h3 style={{ margin: 0 }}>{animal.name}</h3>
                <p style={{ margin: 0, color: 'var(--text-muted)', fontSize: '0.95rem' }}>{animal.highlights}</p>
                <GeneBadgeList genetics={animal.genetics} />
              </div>
            </article>
          ))}
          {showcased.length === 0 && (
            <p style={{ color: 'var(--text-muted)' }}>Noch keine Tiere markiert. Aktivere Tiere im Adminbereich.</p>
          )}
        </div>
      </div>
    </section>
  );
}

function GeneBadgeList({ genetics }) {
  if (!genetics?.length) return null;
  return (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem' }}>
      {genetics.map((entry) => (
        <span
          key={`${entry.gene}-${entry.expression}`}
          style={{
            borderRadius: '999px',
            border: '1px solid rgba(91, 229, 132, 0.4)',
            padding: '0.25rem 0.75rem',
            fontSize: '0.75rem',
            textTransform: 'uppercase',
            letterSpacing: '0.06em'
          }}
        >
          {entry.expression === 'het' ? 'Het ' : entry.expression === 'super' ? 'Super ' : ''}
          {entry.gene}
        </span>
      ))}
    </div>
  );
}

function PrimaryLink({ to, children }) {
  return (
    <Link
      to={to}
      style={{
        borderRadius: '999px',
        padding: '0.85rem 1.6rem',
        background: 'linear-gradient(135deg, #5be584, #1ec07d)',
        color: '#04110b',
        fontWeight: 700,
        letterSpacing: '0.04em'
      }}
    >
      {children}
    </Link>
  );
}

function SecondaryLink({ to, children }) {
  return (
    <Link
      to={to}
      style={{
        borderRadius: '999px',
        padding: '0.75rem 1.4rem',
        background: 'rgba(91, 229, 132, 0.15)',
        border: '1px solid rgba(91, 229, 132, 0.35)',
        color: 'inherit',
        fontWeight: 600
      }}
    >
      {children}
    </Link>
  );
}
