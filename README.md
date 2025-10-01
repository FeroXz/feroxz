# FeroxZ – Reptilien-CMS mit Node.js 18 Backend

FeroxZ läuft jetzt als **Node.js 18 Anwendung** und kombiniert eine clientseitige Single-Page-App mit einem Express-Backend. Das Backend liefert den Initialzustand, speichert sämtliche Änderungen auf dem Dateisystem und stellt API-Endpunkte für Exporte und Resets bereit. Dadurch lassen sich alle Funktionen unverändert weiter nutzen und gleichzeitig komfortabel auf Plattformen wie GitHub (über Actions oder Deployments) hosten.

## Funktionsüberblick

- 🦎 **Tierverwaltung** mit Bildern, Status, Eigenschaften und Beschreibungen.
- 📢 **Tierabgabe-Inserate** inklusive Verknüpfung mit vorhandenen Tieren.
- 📰 **Neuigkeiten & Seiten** mit integriertem Rich-Text-Editor (contenteditable Toolbar).
- 📚 **Pflegeleitfäden** für jede Art mit strukturierten Abschnitten und Quellenangaben.
- 🧬 **Genetikrechner** im MorphMarket-Stil, inkl. Visual-, Het- und Possible-Het-Auswertung sowie Heterodon/Pogona-Gendatenbank.
- 🐍 **Zuchtplanung** mit eigenen oder virtuellen Elterntieren und Projektnotizen.
- 🧾 **Export/Import** sämtlicher Daten als JSON, ideal für Versionsverwaltung in Git.

## Nutzung

1. Repository klonen oder als Template verwenden.
2. Abhängigkeiten installieren: `npm install` (Node.js ≥ 18 erforderlich).
3. Entwicklung starten: `npm run dev` (mit automatischem Reload) oder Produktionsserver: `npm start`.
4. Die Anwendung ist unter `http://localhost:3000` erreichbar. Der **Admin-Bereich** (Passwort: `admin`) funktioniert wie gewohnt; Änderungen werden lokal im Browser und serverseitig unter `data/state.json` gespeichert.
5. Für Deployments können statische Assets weiterhin über GitHub Pages bereitgestellt werden – der Node-Server liefert zusätzlich API-Endpunkte (`/api/state`, `/api/state/reset`) und den initialen Zustand (`/state.js`).

## Technologie

- Node.js 18, Express, Helmet, Compression für das Backend mit Datei-Persistenz.
- Tailwind CDN für Utility-Klassen ohne Build-Step.
- Eigene CSS-Ergänzungen für Glasdesign, Karten und Komponenten.
- Vanilla JavaScript (ES Modules) für State-Management, Routing und Genetiklogik.

## Deployment

- **Node-Hosting (empfohlen):** Über einen Node.js 18 fähigen Dienst (`npm start`). Der Zustand wird automatisch als JSON-Datei persistiert.
- **Statischer Export:** `index.html`, `assets/` und `data/state.json` können auch ohne Node-Server ausgeliefert werden. Der Initialzustand muss dann manuell gepflegt werden.

## Tests

- Syntax-Prüfung der Haupt-JavaScript-Datei: `npm run lint`

## Lizenz

MIT – nutze FeroxZ als Grundlage für eigene statische Reptilienportale oder passe den Funktionsumfang an deine Projekte an.
