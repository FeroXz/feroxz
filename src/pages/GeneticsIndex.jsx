import { Link } from 'react-router-dom';
import { useData } from '../context/DataContext.jsx';

export default function GeneticsIndex() {
  const { species } = useData();

  return (
    <div>
      <section className="section-title">
        <h2>Genetikdatenbank</h2>
        <p>Aktuell gepflegte Arten inklusive Genlisten und Rechner.</p>
      </section>
      <div className="card-grid">
        {species.map((entry) => (
          <article key={entry.slug} className="card">
            <h3>
              {entry.commonName} <span style={{ color: '#64748b', fontSize: '0.9rem' }}>({entry.latinName})</span>
            </h3>
            <p>{entry.description}</p>
            <div className="list-item__meta">
              <span className="badge">{entry.genes.length} Gene</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
              <Link className="button" to={`/genetics/${entry.slug}`}>
                Detailansicht
              </Link>
              <Link className="button button--ghost" to={`/genetics/${entry.slug}/calculator`}>
                Rechner öffnen
              </Link>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}
