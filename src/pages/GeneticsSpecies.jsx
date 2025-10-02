import { Link, useParams } from 'react-router-dom';
import { useData } from '../context/DataContext.jsx';

export default function GeneticsSpecies() {
  const { speciesSlug } = useParams();
  const { species } = useData();

  const entry = species.find((item) => item.slug === speciesSlug);

  if (!entry) {
    return (
      <article className="article">
        <h1>Art nicht gefunden</h1>
        <p>Bitte kehre zur Übersicht zurück und wähle eine verfügbare Art aus.</p>
        <Link className="button" to="/genetics">
          Zurück zur Genetik
        </Link>
      </article>
    );
  }

  return (
    <div className="calculator-grid">
      <section className="article">
        <h1>
          {entry.commonName} <span style={{ color: '#64748b', fontSize: '1rem' }}>({entry.latinName})</span>
        </h1>
        <p>{entry.description}</p>
        <div className="list-item__meta">
          <span className="badge">Lebensraum</span>
          <span>{entry.habitat}</span>
        </div>
        <div className="list-item__meta">
          <span className="badge">Pflege</span>
          <span>{entry.careNotes}</span>
        </div>
        <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', marginTop: '1.5rem' }}>
          <Link className="button" to={`/genetics/${entry.slug}/calculator`}>
            Zum Genetik-Rechner
          </Link>
          <Link className="button button--ghost" to="/genetics">
            Übersicht
          </Link>
        </div>
      </section>

      <section>
        <table className="table">
          <caption>Gene und Vererbungstypen für {entry.commonName}</caption>
          <thead>
            <tr>
              <th>Gen</th>
              <th>Vererbung</th>
              <th>Beschreibung</th>
            </tr>
          </thead>
          <tbody>
            {entry.genes.map((gene) => (
              <tr key={gene.id}>
                <td>{gene.name}</td>
                <td>{mapInheritanceLabel(gene.inheritance)}</td>
                <td>{gene.description}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </section>
    </div>
  );
}

function mapInheritanceLabel(value) {
  switch (value) {
    case 'recessive':
      return 'rezessiv';
    case 'dominant':
      return 'dominant';
    case 'co-dominant':
      return 'co-dominant / unvollständig dominant';
    default:
      return value;
  }
}
