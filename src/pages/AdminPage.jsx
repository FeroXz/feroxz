import { useEffect, useMemo, useState } from 'react';
import { useAppData } from '../context/AppDataContext.jsx';

const AUTH_KEY = 'feroxz-admin-auth';

const emptyAnimal = {
  id: null,
  name: '',
  species: '',
  sex: '♀',
  age: '',
  origin: '',
  highlights: '',
  image: '',
  showcase: false,
  geneticsString: ''
};

export function AdminPage() {
  const {
    state: { settings, animals, genetics },
    dispatch
  } = useAppData();
  const [credentials, setCredentials] = useState({ username: '', password: '' });
  const [isAuthenticated, setIsAuthenticated] = useState(() => {
    if (typeof window === 'undefined') return false;
    return window.localStorage.getItem(AUTH_KEY) === 'true';
  });
  const [settingsDraft, setSettingsDraft] = useState(settings);
  const [editingAnimal, setEditingAnimal] = useState(() => ({ ...emptyAnimal, species: genetics.species[0]?.slug ?? '' }));

  useEffect(() => {
    setSettingsDraft(settings);
  }, [settings]);

  const handleLogin = (event) => {
    event.preventDefault();
    if (credentials.username === 'admin' && credentials.password === '12345678') {
      setIsAuthenticated(true);
      if (typeof window !== 'undefined') {
        window.localStorage.setItem(AUTH_KEY, 'true');
      }
    } else {
      alert('Ungültige Zugangsdaten. Standard: admin / 12345678');
    }
  };

  const handleUpdateSettings = (event) => {
    event.preventDefault();
    dispatch({ type: 'UPDATE_SETTINGS', payload: settingsDraft });
  };

  const handleEditAnimal = (animal) => {
    setEditingAnimal({
      ...animal,
      geneticsString: animal.genetics.map((entry) => `${entry.gene}:${entry.expression}`).join(', ')
    });
  };

  const handleResetAnimalForm = () => {
    setEditingAnimal({ ...emptyAnimal, species: genetics.species[0]?.slug ?? '' });
  };

  const handleSubmitAnimal = (event) => {
    event.preventDefault();
    const parsedGenetics = editingAnimal.geneticsString
      .split(',')
      .map((token) => token.trim())
      .filter(Boolean)
      .map((token) => {
        const [gene, expression = 'visual'] = token.split(':').map((part) => part.trim().toLowerCase());
        return { gene, expression };
      });

    const payload = {
      id: editingAnimal.id,
      name: editingAnimal.name,
      species: editingAnimal.species,
      sex: editingAnimal.sex,
      age: editingAnimal.age,
      origin: editingAnimal.origin,
      highlights: editingAnimal.highlights,
      image: editingAnimal.image,
      showcase: editingAnimal.showcase,
      genetics: parsedGenetics
    };

    dispatch({ type: 'UPSERT_ANIMAL', animal: payload });
    handleResetAnimalForm();
  };

  const handleDeleteAnimal = (id) => {
    if (window.confirm('Tier wirklich löschen?')) {
      dispatch({ type: 'DELETE_ANIMAL', id });
    }
  };

  if (!isAuthenticated) {
    return (
      <section className="container" style={{ display: 'grid', gap: '2rem' }}>
        <form onSubmit={handleLogin} className="glass-panel" style={{ display: 'grid', gap: '1rem', maxWidth: '420px' }}>
          <div>
            <span className="tag">Admin Login</span>
            <h1 style={{ margin: '0.5rem 0 0' }}>Melde dich an</h1>
            <p style={{ color: 'var(--text-muted)' }}>Standard-Zugang: admin / 12345678</p>
          </div>
          <label style={labelStyle}>
            Benutzername
            <input
              type="text"
              value={credentials.username}
              onChange={(event) => setCredentials((prev) => ({ ...prev, username: event.target.value }))}
              style={inputStyle}
            />
          </label>
          <label style={labelStyle}>
            Passwort
            <input
              type="password"
              value={credentials.password}
              onChange={(event) => setCredentials((prev) => ({ ...prev, password: event.target.value }))}
              style={inputStyle}
            />
          </label>
          <button type="submit" style={primaryButtonStyle}>
            Anmelden
          </button>
        </form>
      </section>
    );
  }

  return (
    <section className="container" style={{ display: 'grid', gap: '2rem' }}>
      <SettingsPanel settingsDraft={settingsDraft} setSettingsDraft={setSettingsDraft} onSubmit={handleUpdateSettings} />
      <AnimalPanel
        animals={animals}
        genetics={genetics}
        editingAnimal={editingAnimal}
        setEditingAnimal={setEditingAnimal}
        onSubmit={handleSubmitAnimal}
        onEdit={handleEditAnimal}
        onDelete={handleDeleteAnimal}
        onReset={handleResetAnimalForm}
      />
    </section>
  );
}

function SettingsPanel({ settingsDraft, setSettingsDraft, onSubmit }) {
  return (
    <form onSubmit={onSubmit} className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
      <div>
        <span className="tag">Erscheinungsbild</span>
        <h2 style={{ margin: '0.5rem 0 0' }}>Texte & Version anpassen</h2>
      </div>
      <label style={labelStyle}>
        Seitenname
        <input
          type="text"
          value={settingsDraft.siteName}
          onChange={(event) => setSettingsDraft((prev) => ({ ...prev, siteName: event.target.value }))}
          style={inputStyle}
        />
      </label>
      <label style={labelStyle}>
        Tagline
        <input
          type="text"
          value={settingsDraft.tagline}
          onChange={(event) => setSettingsDraft((prev) => ({ ...prev, tagline: event.target.value }))}
          style={inputStyle}
        />
      </label>
      <label style={labelStyle}>
        Hero Titel
        <input
          type="text"
          value={settingsDraft.heroTitle}
          onChange={(event) => setSettingsDraft((prev) => ({ ...prev, heroTitle: event.target.value }))}
          style={inputStyle}
        />
      </label>
      <label style={labelStyle}>
        Hero Intro
        <textarea
          value={settingsDraft.heroIntro}
          onChange={(event) => setSettingsDraft((prev) => ({ ...prev, heroIntro: event.target.value }))}
          style={{ ...inputStyle, minHeight: '120px' }}
        />
      </label>
      <label style={labelStyle}>
        Footer Version
        <input
          type="text"
          value={settingsDraft.footerVersion}
          onChange={(event) => setSettingsDraft((prev) => ({ ...prev, footerVersion: event.target.value }))}
          style={inputStyle}
        />
      </label>
      <button type="submit" style={primaryButtonStyle}>
        Einstellungen speichern
      </button>
    </form>
  );
}

function AnimalPanel({ animals, genetics, editingAnimal, setEditingAnimal, onSubmit, onEdit, onDelete, onReset }) {
  const speciesOptions = useMemo(() => genetics.species.map((entry) => ({ value: entry.slug, label: entry.name })), [genetics]);

  return (
    <div className="grid two">
      <form onSubmit={onSubmit} className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
        <div>
          <span className="tag">Tier verwalten</span>
          <h2 style={{ margin: '0.5rem 0 0' }}>{editingAnimal.id ? 'Tier bearbeiten' : 'Neues Tier anlegen'}</h2>
        </div>
        <label style={labelStyle}>
          Name
          <input
            type="text"
            value={editingAnimal.name}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, name: event.target.value }))}
            style={inputStyle}
            required
          />
        </label>
        <label style={labelStyle}>
          Art
          <select
            value={editingAnimal.species}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, species: event.target.value }))}
            style={inputStyle}
          >
            {speciesOptions.map((option) => (
              <option key={option.value} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>
        </label>
        <div style={{ display: 'grid', gap: '0.75rem', gridTemplateColumns: 'repeat(auto-fit, minmax(140px, 1fr))' }}>
          <label style={labelStyle}>
            Geschlecht
            <input
              type="text"
              value={editingAnimal.sex}
              onChange={(event) => setEditingAnimal((prev) => ({ ...prev, sex: event.target.value }))}
              style={inputStyle}
            />
          </label>
          <label style={labelStyle}>
            Alter
            <input
              type="text"
              value={editingAnimal.age}
              onChange={(event) => setEditingAnimal((prev) => ({ ...prev, age: event.target.value }))}
              style={inputStyle}
            />
          </label>
        </div>
        <label style={labelStyle}>
          Herkunft
          <input
            type="text"
            value={editingAnimal.origin}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, origin: event.target.value }))}
            style={inputStyle}
          />
        </label>
        <label style={labelStyle}>
          Highlights
          <textarea
            value={editingAnimal.highlights}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, highlights: event.target.value }))}
            style={{ ...inputStyle, minHeight: '100px' }}
          />
        </label>
        <label style={labelStyle}>
          Bild-URL
          <input
            type="url"
            value={editingAnimal.image}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, image: event.target.value }))}
            style={inputStyle}
          />
        </label>
        <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <input
            type="checkbox"
            checked={editingAnimal.showcase}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, showcase: event.target.checked }))}
          />
          <span>Auf Startseite hervorheben</span>
        </label>
        <label style={labelStyle}>
          Genetik (Format: gen:expression, ...)
          <input
            type="text"
            value={editingAnimal.geneticsString}
            onChange={(event) => setEditingAnimal((prev) => ({ ...prev, geneticsString: event.target.value }))}
            style={inputStyle}
            placeholder="albino:visual, toffee-belly:het"
          />
        </label>
        <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap' }}>
          <button type="submit" style={primaryButtonStyle}>
            {editingAnimal.id ? 'Änderungen speichern' : 'Tier anlegen'}
          </button>
          <button type="button" style={secondaryButtonStyle} onClick={onReset}>
            Formular zurücksetzen
          </button>
        </div>
      </form>
      <div className="glass-panel" style={{ display: 'grid', gap: '1rem' }}>
        <div>
          <span className="tag">Bestand</span>
          <h2 style={{ margin: '0.5rem 0 0' }}>Aktive Tiere</h2>
        </div>
        <div className="grid" style={{ gap: '1rem' }}>
          {animals.map((animal) => (
            <article key={animal.id} className="glass-panel light" style={{ display: 'grid', gap: '0.5rem', padding: '1rem 1.25rem' }}>
              <strong>{animal.name}</strong>
              <span style={{ color: 'var(--text-muted)', fontSize: '0.9rem' }}>{animal.species}</span>
              <div style={{ display: 'flex', gap: '0.75rem' }}>
                <button type="button" style={secondaryButtonStyle} onClick={() => onEdit(animal)}>
                  Bearbeiten
                </button>
                <button type="button" style={{ ...secondaryButtonStyle, color: 'var(--danger)' }} onClick={() => onDelete(animal.id)}>
                  Löschen
                </button>
              </div>
            </article>
          ))}
          {animals.length === 0 && <p style={{ color: 'var(--text-muted)' }}>Noch keine Tiere gespeichert.</p>}
        </div>
      </div>
    </div>
  );
}

const labelStyle = {
  display: 'grid',
  gap: '0.35rem',
  fontWeight: 600
};

const inputStyle = {
  borderRadius: '12px',
  padding: '0.65rem 0.9rem',
  border: '1px solid rgba(255,255,255,0.25)',
  background: 'rgba(0,0,0,0.18)',
  color: 'inherit',
  fontSize: '1rem'
};

const primaryButtonStyle = {
  borderRadius: '999px',
  padding: '0.75rem 1.6rem',
  border: 'none',
  background: 'linear-gradient(135deg, #5be584, #1ec07d)',
  color: '#04110b',
  fontWeight: 700,
  letterSpacing: '0.04em',
  cursor: 'pointer'
};

const secondaryButtonStyle = {
  borderRadius: '999px',
  padding: '0.7rem 1.4rem',
  border: '1px solid rgba(255,255,255,0.25)',
  background: 'rgba(255,255,255,0.05)',
  color: 'inherit',
  fontWeight: 600,
  cursor: 'pointer'
};
