import { useMemo, useState } from 'react';
import { useAppData } from '../context/AppDataContext.jsx';
import { mergeOutcomes, parseGeneticsFromAnimal } from '../utils/genetics.js';

export function GeneticsCalculatorPage() {
  const {
    state: { genetics, animals }
  } = useAppData();

  const [speciesSlug, setSpeciesSlug] = useState(genetics.species[0]?.slug ?? '');
  const [parentAExpressions, setParentAExpressions] = useState({});
  const [parentBExpressions, setParentBExpressions] = useState({});

  const species = useMemo(
    () => genetics.species.find((entry) => entry.slug === speciesSlug) ?? genetics.species[0],
    [genetics, speciesSlug]
  );

  const speciesAnimals = useMemo(
    () => animals.filter((animal) => animal.species === species.slug),
    [animals, species]
  );

  const genes = species?.genes ?? [];

  const parentAList = useMemo(
    () =>
      genes
        .map((gene) => ({ gene: gene.slug, expression: parentAExpressions[gene.slug] || 'normal' }))
        .filter((entry) => entry.expression !== 'normal'),
    [genes, parentAExpressions]
  );

  const parentBList = useMemo(
    () =>
      genes
        .map((gene) => ({ gene: gene.slug, expression: parentBExpressions[gene.slug] || 'normal' }))
        .filter((entry) => entry.expression !== 'normal'),
    [genes, parentBExpressions]
  );

  const results = useMemo(() => mergeOutcomes(genes, parentAList, parentBList), [genes, parentAList, parentBList]);

  const handleExpressionChange = (parent, geneSlug, value) => {
    const setter = parent === 'A' ? setParentAExpressions : setParentBExpressions;
    setter((prev) => {
      const next = { ...prev };
      if (!value || value === 'normal') {
        delete next[geneSlug];
      } else {
        next[geneSlug] = value;
      }
      return next;
    });
  };

  const handleSelectAnimal = (parent, animalId) => {
    const setter = parent === 'A' ? setParentAExpressions : setParentBExpressions;
    if (!animalId) {
      setter({});
      return;
    }
    const animal = speciesAnimals.find((item) => item.id === animalId);
    const parsed = parseGeneticsFromAnimal(animal);
    const mapped = Object.fromEntries(parsed.map((entry) => [entry.gene, entry.expression]));
    setter(mapped);
  };

  return (
    <section className="container" style={{ display: 'grid', gap: '2rem' }}>
      <header className="glass-panel light" style={{ display: 'grid', gap: '0.75rem' }}>
        <span className="tag">Genetik Rechner</span>
        <h1 style={{ margin: 0 }}>MorphMarket-inspirierte Ergebnisse</h1>
        <p style={{ color: 'var(--text-muted)', margin: 0 }}>
          Wähle eine Art, definiere die Genetik beider Eltern oder ziehe gespeicherte Tiere heran. Das Ergebnis kombiniert alle
          Gene zu MorphMarket-ähnlichen Bezeichnungen inklusive Prozentangaben.
        </p>
        <label style={{ display: 'grid', gap: '0.35rem' }}>
          <span style={{ fontWeight: 600 }}>Art auswählen</span>
          <select
            value={species.slug}
            onChange={(event) => {
              setSpeciesSlug(event.target.value);
              setParentAExpressions({});
              setParentBExpressions({});
            }}
            style={selectStyle}
          >
            {genetics.species.map((entry) => (
              <option key={entry.slug} value={entry.slug}>
                {entry.name}
              </option>
            ))}
          </select>
        </label>
      </header>

      <div className="grid two">
        <ParentPanel
          label="Elter A"
          genes={genes}
          expressions={parentAExpressions}
          onChange={(gene, value) => handleExpressionChange('A', gene, value)}
          animals={speciesAnimals}
          onSelectAnimal={(id) => handleSelectAnimal('A', id)}
        />
        <ParentPanel
          label="Elter B"
          genes={genes}
          expressions={parentBExpressions}
          onChange={(gene, value) => handleExpressionChange('B', gene, value)}
          animals={speciesAnimals}
          onSelectAnimal={(id) => handleSelectAnimal('B', id)}
        />
      </div>

      <ResultsPanel results={results} genes={genes} />
    </section>
  );
}

function ParentPanel({ label, genes, expressions, onChange, animals, onSelectAnimal }) {
  return (
    <div className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <span className="tag">{label}</span>
          <h2 style={{ margin: '0.5rem 0 0' }}>Genetik definieren</h2>
        </div>
        <select defaultValue="" onChange={(event) => onSelectAnimal(event.target.value)} style={selectStyle}>
          <option value="">Tier auswählen</option>
          {animals.map((animal) => (
            <option key={animal.id} value={animal.id}>
              {animal.name}
            </option>
          ))}
        </select>
      </div>
      <div className="grid" style={{ gap: '0.75rem' }}>
        {genes.map((gene) => (
          <label key={gene.slug} className="glass-panel light" style={{ display: 'grid', gap: '0.35rem' }}>
            <span style={{ fontWeight: 600 }}>{gene.name}</span>
            <span style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>{gene.description}</span>
            <select value={expressions[gene.slug] || 'normal'} onChange={(event) => onChange(gene.slug, event.target.value)} style={selectStyle}>
              <option value="normal">Keine Expression</option>
              <option value="visual">Visual</option>
              {(gene.type === 'recessive' || gene.type === 'incomplete-dominant') && <option value="het">Het</option>}
              {gene.type === 'incomplete-dominant' && <option value="super">Super</option>}
              <option value="possibleHet">Possible Het</option>
            </select>
          </label>
        ))}
      </div>
    </div>
  );
}

function ResultsPanel({ results, genes }) {
  return (
    <div className="glass-panel" style={{ display: 'grid', gap: '1.5rem' }}>
      <div>
        <span className="tag">Ergebnis</span>
        <h2 style={{ margin: '0.5rem 0 0' }}>Zusammenfassung der Nachzuchten</h2>
      </div>
      <div className="grid" style={{ gap: '1rem' }}>
        {results.merged.length ? (
          results.merged.map((entry) => (
            <div key={entry.label} className="glass-panel light" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '1rem 1.25rem' }}>
              <strong>{entry.label}</strong>
              <span style={{ color: 'var(--text-muted)' }}>{entry.probability.toFixed(1)}%</span>
            </div>
          ))
        ) : (
          <p style={{ color: 'var(--text-muted)' }}>Wähle Genetik für beide Eltern, um Ergebnisse zu sehen.</p>
        )}
      </div>
      <section style={{ display: 'grid', gap: '1rem' }}>
        <h3 style={{ margin: 0 }}>Pro-Gene-Analyse</h3>
        <div className="grid" style={{ gap: '1rem' }}>
          {results.perGene.map((entry) => (
            <article key={entry.gene.slug} className="glass-panel light" style={{ display: 'grid', gap: '0.35rem', padding: '1rem 1.25rem' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <strong>{entry.gene.name}</strong>
                <span style={{ color: 'var(--text-muted)', fontSize: '0.85rem' }}>
                  {formatExpression(entry.expressionA)} × {formatExpression(entry.expressionB)}
                </span>
              </div>
              <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
                {Object.entries(entry.probabilities).map(([key, value]) => (
                  <span key={key} style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>
                    {formatOutcomeLabel(entry.gene.type, key)}: {(value * 100).toFixed(1)}%
                  </span>
                ))}
              </div>
            </article>
          ))}
        </div>
      </section>
    </div>
  );
}

function formatOutcomeLabel(type, key) {
  if (type === 'recessive') {
    if (key === 'visual') return 'Visual';
    if (key === 'het') return 'Het';
    return 'Normal';
  }
  if (type === 'incomplete-dominant') {
    if (key === 'super') return 'Super';
    if (key === 'visual') return 'Visual';
    return 'Normal';
  }
  if (type === 'dominant') {
    if (key === 'visual') return 'Visual';
    return 'Normal';
  }
  return key;
}

function formatExpression(expression) {
  switch (expression) {
    case 'visual':
      return 'Visual';
    case 'het':
      return 'Het';
    case 'super':
      return 'Super';
    case 'possibleHet':
      return 'Possible Het';
    default:
      return 'Normal';
  }
}

const selectStyle = {
  borderRadius: '14px',
  padding: '0.6rem 0.9rem',
  border: '1px solid rgba(255,255,255,0.25)',
  background: 'rgba(0,0,0,0.18)',
  color: 'inherit',
  fontSize: '1rem'
};
