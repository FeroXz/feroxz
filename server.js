import express from 'express';
import compression from 'compression';
import helmet from 'helmet';
import morgan from 'morgan';
import { promises as fs } from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import defaultStateSeed from './assets/js/default-state.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;
const DATA_DIR = path.join(__dirname, 'data');
const DATA_FILE = path.join(DATA_DIR, 'state.json');

const deepClone = (value) => JSON.parse(JSON.stringify(value));
const isPlainObject = (value) => typeof value === 'object' && value !== null && !Array.isArray(value);

const mergeDeep = (target, source) => {
  const output = deepClone(target);
  if (!isPlainObject(source)) {
    return output;
  }
  Object.entries(source).forEach(([key, value]) => {
    if (Array.isArray(value)) {
      output[key] = deepClone(value);
    } else if (isPlainObject(value)) {
      output[key] = mergeDeep(target[key] ?? {}, value);
    } else {
      output[key] = value;
    }
  });
  return output;
};

const ensureDataDirectory = async () => {
  await fs.mkdir(DATA_DIR, { recursive: true });
};

const ensureDataFile = async () => {
  await ensureDataDirectory();
  try {
    await fs.access(DATA_FILE);
  } catch (error) {
    const initialState = deepClone(defaultStateSeed);
    await fs.writeFile(DATA_FILE, JSON.stringify(initialState, null, 2), 'utf8');
  }
};

const loadStateFromDisk = async () => {
  await ensureDataFile();
  const raw = await fs.readFile(DATA_FILE, 'utf8');
  let parsed;
  try {
    parsed = JSON.parse(raw);
  } catch (error) {
    console.warn('Ungültige JSON-Daten gefunden, verwende Ausgangszustand.', error);
    parsed = {};
  }
  return mergeDeep(defaultStateSeed, parsed);
};

const persistStateToDisk = async (incoming) => {
  await ensureDataDirectory();
  const merged = mergeDeep(defaultStateSeed, incoming ?? {});
  await fs.writeFile(DATA_FILE, JSON.stringify(merged, null, 2), 'utf8');
  return merged;
};

app.use(helmet());
app.use(compression());
app.use(express.json({ limit: '2mb' }));
app.use(morgan(process.env.NODE_ENV === 'development' ? 'dev' : 'tiny'));

app.use('/assets', express.static(path.join(__dirname, 'assets'), { maxAge: '1d' }));

app.get('/state.js', async (_req, res) => {
  try {
    const state = await loadStateFromDisk();
    res.type('application/javascript');
    res.send(`window.__FEROXZ_INITIAL_STATE__ = ${JSON.stringify(state)};`);
  } catch (error) {
    res.status(500).type('application/javascript');
    res.send('console.error("Konnte Zustand nicht laden.");');
  }
});

app.get('/api/state', async (_req, res) => {
  try {
    const state = await loadStateFromDisk();
    res.json({ state });
  } catch (error) {
    res.status(500).json({ error: 'Zustand konnte nicht geladen werden.' });
  }
});

app.post('/api/state', async (req, res) => {
  try {
    if (!isPlainObject(req.body)) {
      return res.status(400).json({ error: 'Erwartete JSON-Daten im Anfragekörper.' });
    }
    const nextState = await persistStateToDisk(req.body);
    res.json({ state: nextState });
  } catch (error) {
    console.error('Fehler beim Speichern des Zustands', error);
    res.status(500).json({ error: 'Zustand konnte nicht gespeichert werden.' });
  }
});

app.post('/api/state/reset', async (_req, res) => {
  try {
    const resetState = await persistStateToDisk(defaultStateSeed);
    res.json({ state: resetState, status: 'reset' });
  } catch (error) {
    console.error('Fehler beim Zurücksetzen des Zustands', error);
    res.status(500).json({ error: 'Zurücksetzen fehlgeschlagen.' });
  }
});

app.get('/', (_req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('*', (_req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

const start = async () => {
  await ensureDataFile();
  app.listen(PORT, () => {
    console.log(`FeroxZ läuft auf http://localhost:${PORT}`);
  });
};

start();
