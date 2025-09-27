import { useEffect, useMemo, useState } from 'react';
import { useData } from '../../context/DataContext.jsx';

const geneFormTemplate = {
  name: '',
  inheritance: 'recessive',
  description: '',
  visuals: {
    homozygousDominant: '',
    heterozygous: '',
    homozygousRecessive: ''
  }
};

function createGeneForm() {
  return {
    name: geneFormTemplate.name,
    inheritance: geneFormTemplate.inheritance,
    description: geneFormTemplate.description,
    visuals: { ...geneFormTemplate.visuals }
  };
}

export default function AdminGenetics() {
  const { species, updateSpecies, createGene, updateGene, deleteGene } = useData();
  const [selectedSpeciesSlug, setSelectedSpeciesSlug] = useState(species[0]?.slug ?? '');
  const [speciesForm, setSpeciesForm] = useState({ description: '', habitat: '', careNotes: '' });
  const [geneForm, setGeneForm] = useState(() => createGeneForm());
  const [editingGeneId, setEditingGeneId] = useState(null);

  useEffect(() => {
    if (!species.find((entry) => entry.slug === selectedSpeciesSlug)) {
      setSelectedSpeciesSlug(species[0]?.slug ?? '');
    }
  }, [species, selectedSpeciesSlug]);

  const selectedSpecies = useMemo(
    () => species.find((entry) => entry.slug === selectedSpeciesSlug),
    [species, selectedSpeciesSlug]
  );

  useEffect(() => {
    if (!selectedSpecies) {
      return;
    }

    setSpeciesForm({
      description: selectedSpecies.description ?? '',
      habitat: selectedSpecies.habitat ?? '',
      careNotes: selectedSpecies.careNotes ?? ''
    });
    resetGeneForm();
  }, [selectedSpecies]);

  const handleSpeciesSubmit = (event) => {
    event.preventDefault();
    if (!selectedSpecies) {
      return;
    }

    updateSpecies(selectedSpecies.slug, speciesForm);
  };

  const handleGeneSubmit = (event) => {
    event.preventDefault();
    if (!selectedSpecies) {
      return;
    }

    const payload = {
      name: geneForm.name,
      inheritance: geneForm.inheritance,
      description: geneForm.description,
      visuals: { ...geneForm.visuals }
    };

    if (editingGeneId) {
      updateGene(selectedSpecies.slug, editingGeneId, payload);
    } else {
      createGene(selectedSpecies.slug, payload);
    }

    resetGeneForm();
  };

  const handleGeneEdit = (gene) => {
    setEditingGeneId(gene.id);
    setGeneForm({
      name: gene.name,
      inheritance: gene.inheritance,
      description: gene.description,
      visuals: { ...gene.visuals }
    });
  };

  const handleGeneDelete = (geneId) => {
    if (selectedSpecies && window.confirm('Gen wirklich löschen?')) {
      deleteGene(selectedSpecies.slug, geneId);
      resetGeneForm();
    }
  };

  function resetGeneForm() {
    setEditingGeneId(null);
    setGeneForm(createGeneForm());
  }

  return (
    <div className="admin-section">
      <header>
        <h2>Genetik</h2>
        <p>Pflege Artenbeschreibungen und die zugrunde liegenden Gene.</p>
      </header>

      {species.length === 0 ? (
        <p>Es sind derzeit keine Arten hinterlegt.</p>
      ) : (
        <div className="form-grid">
          <label>
            Art auswählen
            <select value={selectedSpeciesSlug} onChange={(event) => setSelectedSpeciesSlug(event.target.value)}>
              {species.map((entry) => (
                <option key={entry.slug} value={entry.slug}>
                  {entry.commonName} ({entry.latinName})
                </option>
              ))}
            </select>
          </label>
        </div>
      )}

      {selectedSpecies && (
        <>
          <form className="form-grid" onSubmit={handleSpeciesSubmit}>
            <label>
              Beschreibung
              <textarea
                value={speciesForm.description}
                onChange={(event) => setSpeciesForm((prev) => ({ ...prev, description: event.target.value }))}
                rows={5}
              />
            </label>
            <label>
              Lebensraum
              <textarea
                value={speciesForm.habitat}
                onChange={(event) => setSpeciesForm((prev) => ({ ...prev, habitat: event.target.value }))}
                rows={3}
              />
            </label>
            <label>
              Pflegehinweise
              <textarea
                value={speciesForm.careNotes}
                onChange={(event) => setSpeciesForm((prev) => ({ ...prev, careNotes: event.target.value }))}
                rows={4}
              />
            </label>
            <button type="submit" className="button">
              Art aktualisieren
            </button>
          </form>

          <form className="form-grid" onSubmit={handleGeneSubmit}>
            <h3>{editingGeneId ? 'Gen bearbeiten' : 'Neues Gen anlegen'}</h3>
            <label>
              Bezeichnung
              <input
                value={geneForm.name}
                onChange={(event) => setGeneForm((prev) => ({ ...prev, name: event.target.value }))}
                required
              />
            </label>
            <label>
              Vererbungstyp
              <select
                value={geneForm.inheritance}
                onChange={(event) => setGeneForm((prev) => ({ ...prev, inheritance: event.target.value }))}
              >
                <option value="recessive">rezessiv</option>
                <option value="dominant">dominant</option>
                <option value="co-dominant">co-dominant / unvollständig dominant</option>
              </select>
            </label>
            <label>
              Beschreibung
              <textarea
                value={geneForm.description}
                onChange={(event) => setGeneForm((prev) => ({ ...prev, description: event.target.value }))}
                rows={4}
              />
            </label>
            <label>
              Homozygot dominant
              <input
                value={geneForm.visuals.homozygousDominant}
                onChange={(event) =>
                  setGeneForm((prev) => ({
                    ...prev,
                    visuals: { ...prev.visuals, homozygousDominant: event.target.value }
                  }))
                }
                placeholder="z. B. Normal"
              />
            </label>
            <label>
              Heterozygot
              <input
                value={geneForm.visuals.heterozygous}
                onChange={(event) =>
                  setGeneForm((prev) => ({
                    ...prev,
                    visuals: { ...prev.visuals, heterozygous: event.target.value }
                  }))
                }
                placeholder="z. B. Het Albino"
              />
            </label>
            <label>
              Homozygot rezessiv / Superform
              <input
                value={geneForm.visuals.homozygousRecessive}
                onChange={(event) =>
                  setGeneForm((prev) => ({
                    ...prev,
                    visuals: { ...prev.visuals, homozygousRecessive: event.target.value }
                  }))
                }
                placeholder="z. B. Albino"
              />
            </label>
            <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
              <button type="submit" className="button">
                {editingGeneId ? 'Gen aktualisieren' : 'Gen speichern'}
              </button>
              {editingGeneId && (
                <button type="button" className="button button--ghost" onClick={resetGeneForm}>
                  Abbrechen
                </button>
              )}
            </div>
          </form>

          <div className="list">
            {selectedSpecies.genes.map((gene) => (
              <article key={gene.id} className="list-item">
                <h3>{gene.name}</h3>
                <div className="list-item__meta">
                  <span>{mapInheritanceLabel(gene.inheritance)}</span>
                </div>
                <p>{gene.description}</p>
                <ul>
                  <li>Homozygot dominant: {gene.visuals.homozygousDominant}</li>
                  <li>Heterozygot: {gene.visuals.heterozygous}</li>
                  <li>Homozygot rezessiv / Super: {gene.visuals.homozygousRecessive}</li>
                </ul>
                <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap' }}>
                  <button type="button" className="button button--ghost" onClick={() => handleGeneEdit(gene)}>
                    Bearbeiten
                  </button>
                  <button type="button" className="button button--danger" onClick={() => handleGeneDelete(gene.id)}>
                    Löschen
                  </button>
                </div>
              </article>
            ))}
          </div>
        </>
      )}
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
