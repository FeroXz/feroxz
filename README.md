# Feroxz React CMS

Ein leichtgewichtiges, vollständig clientseitiges CMS auf React-Basis. Beiträge, statische Seiten, Galerie-Einträge und die
Genetik-Datenbank für *Pogona vitticeps* sowie *Heterodon nasicus* werden über den integrierten Adminbereich gepflegt und im
Browser (Local Storage) gespeichert. Damit eignet sich das Projekt für Präsentationen, Demozwecke oder als Ausgangspunkt für
eine Headless-Anbindung.

## Highlights

- 🧭 **SPA mit React & React Router** – schnelle Navigation ohne Seitenneuladung.
- 📝 **Blog und statische Seiten** – Inhalte lassen sich sofort im Adminbereich anlegen und bearbeiten.
- 🖼️ **Galerie mit Datei-Upload** – unterstützt Links oder lokale Bilder (werden als Data-URL im Speicher abgelegt).
- 🧬 **Genetik-Datenbank & Rechner** – inklusive Punnett-Rechner für rezessive, dominante und co-dominante Gene.
- 🔐 **Admin-Login** – Standardzugang `admin` / `12345678`, Session wird in `sessionStorage` gehalten.
- 💾 **Persistenz im Browser** – sämtliche Daten werden im `localStorage` gesichert; ein Reset ist über das Dashboard möglich.

## Entwicklung starten

Voraussetzung ist eine aktuelle Node.js-Version (>= 18).

```bash
npm install
npm run dev
```

Der Development-Server (Vite) läuft anschließend unter `http://localhost:5173`. Änderungen an den Quellen werden automatisch
neu geladen.

## Produktion & Deployment

1. **Build erzeugen**
   ```bash
   npm run build
   ```
   Der optimierte Output landet im Ordner `dist/`.

2. **Statisches Hosting**
   Lade den Inhalt von `dist/` auf einen beliebigen Webspace oder nutze Dienste wie Netlify/Vercel. Da die Anwendung komplett
   clientseitig arbeitet, ist kein Backend erforderlich.

3. **Pflege über den Adminbereich**
   Nach dem ersten Aufruf stehen sämtliche Standarddaten bereit. Navigiere zu `/admin`, melde dich mit `admin` / `12345678` an
   und passe Inhalte, Galerie sowie Genetik-Einträge an. Alle Änderungen bleiben im Browser gespeichert.

> **Hinweis:** Bei einem Browser-Reset (Cookies/Website-Daten löschen) gehen die Inhalte verloren. Über das Dashboard kann
> jederzeit auf die Demo-Daten zurückgesetzt werden.

## Struktur

```
├── index.html              # Vite Entry Point
├── public/                 # Statische Assets (z. B. Favicon)
├── src/
│   ├── App.jsx             # Routing-Konfiguration
│   ├── components/         # Layout- & Helper-Komponenten
│   ├── context/            # Auth- und Daten-Context (Local Storage)
│   ├── pages/              # Öffentliche Seiten & Admin-Ansichten
│   ├── utils/              # Hilfsfunktionen (ID-Generator)
│   └── index.css           # Zentrales Styling
└── vite.config.js          # Build-Konfiguration
```

## Genetik-Rechner

- Unterstützt die vorinstallierten Arten *Pogona vitticeps* und *Heterodon nasicus* inklusive Gene wie Albino, Hypomelanistic
  oder Anaconda.
- Der Rechner bildet Punnett-Quadrate ab und zeigt Wahrscheinlichkeiten für Genotypen & Phänotypen.
- Über den Adminbereich lassen sich weitere Gene anlegen, inklusive Beschreibung und Bezeichnungen für Homo-/Heterozygot.

## Lizenz

Dieses Projekt ist frei anpassbar und dient als Beispiel für eine React-basierte Single-Page-Anwendung mit CMS-Funktionalität.
