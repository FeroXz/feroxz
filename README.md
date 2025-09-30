# FeroxZ – PHP Reptile CMS

FeroxZ ist ein leichtgewichtiges, auf PHP 8.3 und SQLite basierendes CMS für Reptilienhalter. Es vereint Tierverwaltung, Tierabgabe, private Tierakten sowie ein Admin-Backend mit granularen Berechtigungen. Alle Inhalte werden persistiert in einer lokalen SQLite-Datenbank gespeichert, Medien landen im Verzeichnis `uploads/`.

## Kernfunktionen

- 🦎 **Tierverwaltung** mit Art, Genetik, Herkunft, Besonderheiten, Bildern, Showcase-Flag, Besitzerzuordnung und privater Sichtbarkeit.
- 🔒 **„Meine Tiere“** – angemeldete Benutzer sehen ausschließlich ihre privaten Tiere inklusive Genetikprofil für den Rechner.
- 📨 **Tierabgabe-Workflow** mit öffentlichen Inseraten, Kontaktformular und Nachrichteneingang für Administrator*innen.
- 📚 **Pflegeleitfaden** mit ausführlich recherchierten Artikeln zu *Pogona vitticeps* und *Heterodon nasicus*.
- 📰 **Seiten- & Beitragsverwaltung** inklusive WYSIWYG-freundlicher HTML-Felder für statische Seiten und Journal-Artikel.
- 🖼️ **Galerie** mit Upload-Unterstützung für Terrarien- und Morph-Impressionen.
- 🧭 **Menü-Builder** zum Ordnen, Ausblenden oder Verlinken von Routen, Seiten und externen Zielen.
- 🧬 **Genetikdatenbank & MorphMarket-inspirierter Rechner** mit allen aktuell gepflegten Varianten für Bartagamen und Hakennasennattern.
- ⚙️ **Einstellungen** für Seitentitel, Untertitel, Hero-/Abgabe-Text, Kontaktadresse und Footer (inkl. Versionshinweis).
- 👥 **Benutzer- & Rechteverwaltung**: Admins können weitere Accounts mit selektiven Rechten (Tiere, Adoption, Inhalte, Einstellungen) anlegen.
- 📈 **Dashboard** mit Kennzahlen zu Bestand, Medien, Seiten sowie Adoptionsanfragen.
- 💾 **Persistente Speicherung** per SQLite – keine zusätzliche Server-Software notwendig.

## Systemvoraussetzungen

| Komponente | Anforderung |
| ---------- | ----------- |
| PHP        | ≥ 8.3 mit PDO-SQLite, session, fileinfo |
| Webserver  | Apache, Nginx oder kompatibel (z. B. shared hosting) |
| Dateirechte | Schreibrechte für `storage/` und `uploads/` |

## Installation

1. **Dateien hochladen** – den Inhalt dieses Repositories auf den Webspace kopieren (z. B. via FTP oder Git-Deploy).
2. **Verzeichnisse beschreibbar machen**:
   ```bash
   chmod -R 775 storage uploads
   ```
3. **Aufruf im Browser** – `index.php` unter `public/` dient als Front-Controller. Richte den Dokumentenstamm deines Webservers auf `public/` aus.
4. **Erstanmeldung** – Standard-Zugangsdaten: Benutzername `admin`, Passwort `12345678`. Nach dem Login können weitere Benutzer erstellt und Passwörter geändert werden.

> Hinweis: Beim ersten Start wird automatisch eine SQLite-Datenbank unter `storage/database.sqlite` angelegt sowie ein Admin-Benutzer erzeugt.

## Ordnerstruktur

```
feroxz/
├── app/                 # PHP-Logik, Datenbank, Helper
├── public/
│   ├── assets/          # Stylesheet
│   ├── index.php        # Front-Controller
│   └── views/           # Öffentliche und Admin-Templates
├── storage/             # SQLite-Datenbank (wird zur Laufzeit angelegt)
├── uploads/             # Hochgeladene Medien (per .gitignore ausgenommen)
└── README.md
```

## Adminbereich & Workflows

- **Dashboard** – Überblick über Tiere, Abgabeinträge, Journal-Status und eingegangene Nachrichten.
- **Tiere** – CRUD für Tiere inkl. Upload, Besitzerzuordnung und Genetikprofil für den Rechner.
- **Tierabgabe** – Inserate verwalten, Tiere aus dem Bestand übernehmen, Preis/Status pflegen.
- **Anfragen** – Einsicht in alle Adoption-Anfragen, direkte Antwort via `mailto:`.
- **Galerie** – Medien für die öffentliche Galerie hochladen und beschreiben.
- **Seiten & Beiträge** – CMS-Funktionen für statische Inhalte und das Journal.
- **Pflegeleitfäden** – Texte zu Haltung, Ernährung und Gesundheit aktualisieren.
- **Menü** – Navigationspunkte ordnen, verknüpfen oder ausblenden.
- **Genetik** – Spezies beschreiben, Gene ergänzen und den Rechner konfigurieren.
- **Einstellungen** – Seitentexte und Kontaktadresse aktualisieren.
- **Benutzer** – Nur für Admins sichtbar. Neue Benutzer mit selektiven Rechten anlegen.

## Styling

Das Theme nutzt Glas-/Neon-Akzente inspiriert von tropischen Terrarien. Anpassungen erfolgen im Stylesheet `public/assets/style.css`.

## Entwicklung (lokal)

Ein PHP-Entwicklungsserver reicht aus:

```bash
cd public
php -S localhost:8000
```

Danach im Browser `http://localhost:8000/index.php` öffnen.

## Tests

Syntax-Check der PHP-Dateien:

```bash
find public app -name "*.php" -print0 | xargs -0 -n1 php -l
```

## Standard-Login

- Benutzername: `admin`
- Passwort: `12345678`

Bitte ändere das Passwort nach der ersten Anmeldung über die Benutzerverwaltung.
