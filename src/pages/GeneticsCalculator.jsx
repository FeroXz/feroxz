import { useEffect, useMemo, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { useData } from '../context/DataContext.jsx';

export default function GeneticsCalculator() {
  const { speciesSlug } = useParams();
  const { species } = useData();

  const entry = species.find((item) => item.slug === speciesSlug);
  const [selectedGeneId, setSelectedGeneId] = useState(() => entry?.genes[0]?.id ?? '');

  useEffect(() => {
    if (!entry) {
      return;
    }

    if (!entry.genes.find((gene) => gene.id === selectedGeneId)) {
      setSelectedGeneId(entry.genes[0]?.id ?? '');
    }
  }, [entry, selectedGeneId]);

  const selectedGene = useMemo(
    () => entry?.genes.find((gene) => gene.id === selectedGeneId),
    [entry, selectedGeneId]
  );

  const genotypeOptions = useMemo(() => getGenotypeOptions(selectedGene), [selectedGene]);

  const [parentA, setParentA] = useState(() => genotypeOptions[0]?.value ?? 'HH');
  const [parentB, setParentB] = useState(() => genotypeOptions[0]?.value ?? 'HH');

  useEffect(() => {
    if (genotypeOptions.length === 0) {
      return;
    }

    if (!genotypeOptions.find((option) => option.value === parentA)) {
      setParentA(genotypeOptions[0].value);
    }

    if (!genotypeOptions.find((option) => option.value === parentB)) {
      setParentB(genotypeOptions[0].value);
    }
  }, [genotypeOptions, parentA, parentB]);

  const results = useMemo(
    () => (selectedGene ? calculateGene(selectedGene, parentA, parentB) : null),
    [selectedGene, parentA, parentB]
  );

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
        <h1>Genetik-Rechner · {entry.commonName}</h1>
        <p>
          Vergleiche zwei Elterntiere und ermittle die Wahrscheinlichkeiten für Genkombinationen. Der Rechner nutzt klassische
          Punnett-Quadrate und deckt rezessive, dominante sowie co-dominante Vererbung ab.
        </p>
        <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', marginTop: '1.5rem' }}>
          <Link className="button" to={`/genetics/${entry.slug}`}>
            Zur Artbeschreibung
          </Link>
          <Link className="button button--ghost" to="/genetics">
            Artenübersicht
          </Link>
        </div>
      </section>

      <section className="admin-section">
        <header>
          <h2>Einstellungen</h2>
          <p>Wähle Gen und Genotypen der Eltern aus.</p>
        </header>
        <div className="form-grid">
          <label>
            Gen auswählen
            <select value={selectedGeneId} onChange={(event) => setSelectedGeneId(event.target.value)}>
              {entry.genes.map((gene) => (
                <option key={gene.id} value={gene.id}>
                  {gene.name}
                </option>
              ))}
            </select>
          </label>
          <label>
            Elter 1
            <select value={parentA} onChange={(event) => setParentA(event.target.value)}>
              {genotypeOptions.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          </label>
          <label>
            Elter 2
            <select value={parentB} onChange={(event) => setParentB(event.target.value)}>
              {genotypeOptions.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          </label>
        </div>
        {selectedGene && (
          <div className="calculator-results">
            <div className="list-item">
              <strong>Vererbungstyp:</strong>
              <span>{mapInheritanceLabel(selectedGene.inheritance)}</span>
              <p>{selectedGene.description}</p>
              <ul>
                <li>
                  Homozygot dominant: <strong>{selectedGene.visuals.homozygousDominant}</strong>
                </li>
                <li>
                  Heterozygot: <strong>{selectedGene.visuals.heterozygous}</strong>
                </li>
                <li>
                  Homozygot rezessiv: <strong>{selectedGene.visuals.homozygousRecessive}</strong>
                </li>
              </ul>
            </div>
            {results && results.rows.length > 0 && (
              <div className="list-item">
                <strong>Ergebnis:</strong>
                <p>{results.summary}</p>
              </div>
            )}
          </div>
        )}
      </section>

      {results && results.rows.length > 0 && (
        <section>
          <table className="table">
            <caption>Wahrscheinlichkeiten für {selectedGene?.name}</caption>
            <thead>
              <tr>
                <th>Genotyp</th>
                <th>Phänotyp</th>
                <th>Chance</th>
              </tr>
            </thead>
            <tbody>
              {results.rows.map((row) => (
                <tr key={row.genotype}>
                  <td>{row.genotype}</td>
                  <td>{row.phenotype}</td>
                  <td>{row.probability.toFixed(2)}%</td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      )}
    </div>
  );
}

function getGenotypeOptions(gene) {
  if (!gene) {
    return [];
  }

  const base = [
    {
      value: 'HH',
      label: `Homozygot dominant – ${gene.visuals.homozygousDominant}`
    },
    {
      value: 'Hh',
      label: `Heterozygot – ${gene.visuals.heterozygous}`
    },
    {
      value: 'hh',
      label: `Homozygot rezessiv – ${gene.visuals.homozygousRecessive}`
    }
  ];

  if (gene.inheritance === 'dominant') {
    base[0].label = `Homozygot dominant – ${gene.visuals.homozygousDominant}`;
    base[1].label = `Heterozygot – ${gene.visuals.heterozygous}`;
    base[2].label = `Homozygot rezessiv – ${gene.visuals.homozygousRecessive}`;
  }

  return base;
}

function calculateGene(gene, parentA, parentB) {
  const gametesA = expandGametes(parentA);
  const gametesB = expandGametes(parentB);
  const total = gametesA.length * gametesB.length;
  const tally = new Map();

  gametesA.forEach((alleleA) => {
    gametesB.forEach((alleleB) => {
      const genotype = normalizeGenotype(alleleA, alleleB);
      const key = genotype;
      const phenotypeKey = getPhenotypeKey(genotype);
      const phenotype = gene.visuals[phenotypeKey];
      const current = tally.get(key) ?? { genotype: key, count: 0, phenotype };
      current.count += 1;
      tally.set(key, current);
    });
  });

  const rows = Array.from(tally.values())
    .map((entry) => ({
      genotype: entry.genotype,
      phenotype: entry.phenotype,
      probability: (entry.count / total) * 100
    }))
    .sort((a, b) => b.probability - a.probability);

  const summary = rows
    .map((row) => `${row.probability.toFixed(2)}% ${row.phenotype} (${row.genotype})`)
    .join(' · ');

  return { rows, summary };
}

function expandGametes(genotype) {
  switch (genotype) {
    case 'HH':
      return ['H'];
    case 'hh':
      return ['h'];
    default:
      return ['H', 'h'];
  }
}

function normalizeGenotype(a, b) {
  if (a === 'H' && b === 'H') {
    return 'HH';
  }

  if (a === 'h' && b === 'h') {
    return 'hh';
  }

  return 'Hh';
}

function getPhenotypeKey(genotype) {
  switch (genotype) {
    case 'HH':
      return 'homozygousDominant';
    case 'hh':
      return 'homozygousRecessive';
    default:
      return 'heterozygous';
  }
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
