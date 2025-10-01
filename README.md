# FeroxZ – Statisches Reptilien-CMS für GitHub Pages

FeroxZ wurde vollständig auf eine **clientseitige Single-Page-Anwendung** umgestellt und benötigt keine Serverskripte mehr. Alle Inhalte – Tiere, Pflegeleitfäden, Seiten, Neuigkeiten, Genetikdaten und Zuchtpläne – werden direkt im Browser verwaltet und können per JSON exportiert werden, um die Seite als statisches Projekt auf GitHub Pages zu hosten.

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
2. Die Seite lokal öffnen (`index.html`) oder per GitHub Pages bereitstellen.
3. Im Browser den **Admin-Bereich** (Passwort: `admin`) nutzen, um Inhalte anzulegen, Daten zu exportieren oder zu importieren.
4. Änderungen werden im `localStorage` des Browsers gespeichert – für den Produktivbetrieb die JSON-Datei exportieren und im Repository versionieren.

## Technologie

- Tailwind CDN für Utility-Klassen ohne Build-Step.
- Eigene CSS-Ergänzungen für Glasdesign, Karten und Komponenten.
- Vanilla JavaScript (ES Modules) für State-Management, Routing und Genetiklogik.
- Keine externen Abhängigkeiten, keine Build-Tools erforderlich.

## Deployment auf GitHub Pages

1. Repository pushen.
2. In den Repository-Einstellungen unter **Pages** den Branch `main` (oder `work`) und das Wurzelverzeichnis auswählen.
3. Nach dem Deployment ist die App unter `https://<username>.github.io/<repo>/` verfügbar.
4. Datenänderungen erfolgen weiterhin im Browser, Export/Import synchronisiert den Stand mit Git.

## Tests

Da ausschließlich statische Dateien verwendet werden, sind keine PHP- oder Node-Prüfungen notwendig. Funktionale Tests erfolgen direkt im Browser.

## Lizenz

MIT – nutze FeroxZ als Grundlage für eigene statische Reptilienportale oder passe den Funktionsumfang an deine Projekte an.
