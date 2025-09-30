# FeroxZ – React Reptile CMS

Eine moderne Single-Page-Anwendung auf Basis von React und Vite, optimiert für den Einsatz auf Debian 12 (oder vergleichbaren) Servern. Alle Inhalte – Tierdaten, Pflegeleitfäden sowie Genetikinformationen – werden im Browser über `localStorage` persistiert und können ohne serverseitige Komponenten betrieben werden.

## Kernfunktionen

- 🦎 **Pflegeleitfäden** für *Pogona vitticeps* und *Heterodon nasicus* mit ausführlichen Haltungsempfehlungen
- 🧬 **Genetik-Rechner** nach MorphMarket-Vorbild inklusive Het/Super-Kombinationen und Prozentangaben
- 📸 **Tierverwaltung** mit Bildern, Herkunft, Besonderheiten und Showcase-Flag für die Startseite
- 🎨 **Dark/Light Mode** mit speziellem Glas-/Reptilien-Theme
- 🔐 **Adminbereich** (Login `admin` / `12345678`) zum Anpassen von Seitentexten und Tierdaten
- 🛠️ **Deploy-Skript** (`scripts/deploy_latest_pr.sh`) das den neuesten Pull Request automatisch ausrollt

## Systemvoraussetzungen (Debian 12)

| Komponente | Versionsempfehlung |
| ---------- | ------------------ |
| Node.js    | 18 LTS oder 20 LTS |
| npm        | ≥ 9                |
| Git        | ≥ 2.34             |
| curl + jq  | Für das Deploy-Skript |

### Node.js installieren

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs build-essential
```

### jq installieren (für das Deploy-Skript)

```bash
sudo apt-get install -y jq
```

## Lokale Entwicklung

```bash
npm install
npm run dev
```

Die Entwicklungsumgebung startet auf `http://localhost:5173`.

## Produktion/Deployment auf Debian 12

1. **Repository klonen** (oder via FTP/SCP auf den Server bringen):
   ```bash
   git clone https://github.com/<dein-account>/feroxz.git
   cd feroxz
   ```
2. **Abhängigkeiten installieren & Build erzeugen**:
   ```bash
   npm install
   npm run build
   ```
3. **Statisches Bundle ausliefern** – z. B. via Nginx:
   ```nginx
   server {
     listen 80;
     server_name deine-domain.tld;
     root /var/www/feroxz/dist;
     index index.html;

     location / {
       try_files $uri /index.html;
     }
   }
   ```
4. **Preview-Server (optional)**: Für schnelle Tests kann `npm run preview -- --host 0.0.0.0 --port 4173` genutzt und via `systemd` oder `pm2` als Dienst betrieben werden.

## Automatisches Ausrollen des neuesten Pull Requests

Das Skript `scripts/deploy_latest_pr.sh` lädt den aktuellsten offenen Pull Request von GitHub, checkt ihn lokal aus und baut anschließend das Produktionsbundle.

```bash
export GITHUB_OWNER=<organisation-oder-user>
export GITHUB_REPO=<repository-name>
# Optional: Personal Access Token für private Repos oder höhere Rate Limits
export GITHUB_TOKEN=<ghp_xxx>

./scripts/deploy_latest_pr.sh
```

> Das Skript erwartet, dass `origin` auf das GitHub-Repository zeigt und `jq` sowie `curl` verfügbar sind. Nach erfolgreichem Build kann das Bundle wie gewohnt aus `dist/` bereitgestellt werden.

## Projektstruktur

```
feroxz/
├── public/            # Statische Assets (index.html, Favicon)
├── src/
│   ├── components/    # Layout- und UI-Bausteine
│   ├── context/       # Globaler Daten- und Settings-Store
│   ├── pages/         # Router-basierte Seiten
│   └── utils/         # Hilfsfunktionen & Seed-Daten
├── scripts/           # Deployment-/Hilfsskripte
├── package.json
└── vite.config.js
```

## Tests & Qualitätssicherung

- `npm run build` – baut das Produktionsbundle (nutzt Vite-Build)
- Optional können zusätzliche Tools wie `npm run lint` ergänzt werden.

## Standard-Login

- Benutzername: `admin`
- Passwort: `12345678`

Diese Zugangsdaten werden ausschließlich clientseitig überprüft und können im Adminbereich über eigene Mechanismen ersetzt werden (z. B. Anpassung der Komponenten oder Integration eines Backends).
