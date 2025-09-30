import { useMemo, useState } from 'react';
import { useAppData } from '../context/AppDataContext.jsx';

export function AnimalsPage() {
  const {
    state: { animals, genetics }
  } = useAppData();
  const [filter, setFilter] = useState('all');

  const speciesOptions = useMemo(() => genetics.species.map((entry) => entry.slug), [genetics]);

  const filteredAnimals = animals.filter((animal) => filter === 'all' || animal.species === filter);

  return (
    <section className="container" style={{ display: 'grid', gap: '2rem' }}>
      <header className="glass-panel light" style={{ display: 'grid', gap: '0.75rem' }}>
        <span className="tag">Tierübersicht</span>
        <h1 style={{ margin: 0 }}>Deine dokumentierten Tiere</h1>
        <p style={{ color: 'var(--text-muted)', margin: 0 }}>
          Jedes Tier lässt sich mit Herkunft, Besonderheiten und Genetik festhalten. Markiere Favoriten als Showcase, um sie
          auf der Startseite hervorzuheben.
        </p>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.75rem' }}>
          <FilterChip label="Alle" active={filter === 'all'} onClick={() => setFilter('all')} />
          {speciesOptions.map((slug) => (
            <FilterChip key={slug} label={slug} active={filter === slug} onClick={() => setFilter(slug)} />
          ))}
        </div>
      </header>

      <div className="grid two">
        {filteredAnimals.map((animal) => (
          <article key={animal.id} className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
            <div
              style={{
                borderRadius: '18px',
                overflow: 'hidden',
                boxShadow: 'var(--shadow)',
                aspectRatio: '4 / 3',
                backgroundImage: `url(${animal.image})`,
                backgroundSize: 'cover',
                backgroundPosition: 'center'
              }}
            />
            <div style={{ display: 'grid', gap: '0.5rem' }}>
              <h2 style={{ margin: 0 }}>{animal.name}</h2>
              <p style={{ margin: 0, color: 'var(--text-muted)' }}>{animal.highlights}</p>
              <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', color: 'var(--text-muted)', fontSize: '0.9rem' }}>
                <span>Alter: {animal.age}</span>
                <span>Geschlecht: {animal.sex}</span>
                <span>Herkunft: {animal.origin}</span>
              </div>
              <GeneList genetics={animal.genetics} />
            </div>
          </article>
        ))}
        {filteredAnimals.length === 0 && (
          <p style={{ color: 'var(--text-muted)' }}>
            Noch keine Tiere für diesen Filter hinterlegt. Ergänze Tiere im Adminbereich.
          </p>
        )}
      </div>
    </section>
  );
}

function FilterChip({ label, active, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      style={{
        borderRadius: '999px',
        padding: '0.5rem 1.25rem',
        border: active ? '1px solid rgba(91, 229, 132, 0.6)' : '1px solid rgba(255,255,255,0.2)',
        background: active ? 'rgba(91, 229, 132, 0.2)' : 'rgba(255,255,255,0.08)',
        color: 'inherit',
        cursor: 'pointer',
        fontWeight: 600,
        letterSpacing: '0.03em'
      }}
    >
      {label}
    </button>
  );
}

function GeneList({ genetics }) {
  if (!genetics?.length) return null;
  return (
    <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
      {genetics.map((entry) => (
        <span
          key={`${entry.gene}-${entry.expression}`}
          style={{
            borderRadius: '999px',
            padding: '0.3rem 0.9rem',
            background: 'rgba(91, 229, 132, 0.15)',
            border: '1px solid rgba(91, 229, 132, 0.35)',
            fontSize: '0.8rem',
            letterSpacing: '0.05em',
            textTransform: 'uppercase'
          }}
        >
          {entry.expression === 'het' ? 'Het ' : entry.expression === 'super' ? 'Super ' : ''}
          {entry.gene}
        </span>
      ))}
    </div>
  );
}
