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

- Öffentliche Startseite mit Kartenlayout für Blog-Beiträge
- Individuelle Inhaltsseiten über frei wählbare Slugs
- Galerie inkl. Datei-Uploads (z. B. Bilder) über das Backend
- Passwortgeschützter Adminbereich mit Session-Verwaltung
- SQLite-Datenbank wird automatisch initialisiert und benötigt keinen separaten Server
- Genetik-Datenbank für *Pogona vitticeps* und *Heterodon nasicus* inkl. Rechner für mögliche Nachzuchten
- Installationsassistent, der Voraussetzungen prüft und den ersten Administrator anlegt

## Anforderungen

- PHP 8.3 oder höher (getestet mit PHP 8.3 und PHP 8.4)
- Aktivierte Erweiterungen: `pdo_sqlite`, `sqlite3`, `fileinfo` (für Dateiuploads empfehlenswert)
- Schreibrechte für den Ordner `static/uploads/`

## Installation & Deployment auf Shared Hosting


1. **Dateien hochladen**
   Übertrage per FTP/SFTP die Verzeichnisse `public/`, `static/` sowie die Projektwurzel (inkl. `cms.db`, falls bereits vorhanden) in dein Webverzeichnis. Die Datenbankdatei wird beim ersten Aufruf automatisch angelegt.
2. **Dokument-Root setzen**
   Konfiguriere dein Hosting so, dass `public/` als Document Root dient. Nur so greifen die Routen und statischen Assets korrekt.
3. **Schreibrechte anpassen**
   Stelle sicher, dass der Webserver in `static/uploads/` schreiben darf (`chmod 775 static/uploads` bzw. über das Hosting-Panel). Der Ordner wird automatisch erstellt, falls er fehlt.
4. **Installer ausführen**
   Rufe `https://deinedomain.tld/install` auf. Der Assistent prüft PHP-Version, Erweiterungen sowie Dateirechte und führt dich durch das Anlegen des ersten Administrator-Kontos.
5. **Website aufrufen**
   Nach erfolgreicher Installation steht dir die Startseite zur Verfügung, `/admin` führt in den Login-Bereich.

## Lokale Entwicklung

Für lokale Tests kannst du den integrierten PHP-Server verwenden:

```bash
php -S localhost:8000 -t public
```

Die Anwendung erstellt beim ersten Aufruf `cms.db` in der Projektwurzel. Hochgeladene Dateien landen unter `static/uploads/`.

## Genetik-Datenbank & Rechner

- Im Adminbereich findest du unter „Genetik“ die hinterlegten Arten und kannst neue Gene hinzufügen oder bestehende bearbeiten.
- Der öffentliche Bereich `/genetics` listet alle Arten, bietet Detailseiten zu den Genen und einen Rechner pro Art (z. B. `/genetics/pogona-vitticeps/calculator`).
- Der Rechner unterstützt rezessive und (un-)vollständig dominante Vererbung, ermittelt die Wahrscheinlichkeiten pro Gen und fasst alle Kombinationen übersichtlich zusammen.

## Backup & Wartung

- Sichere regelmäßig `cms.db` sowie den Ordner `static/uploads/`.
- Administrator-Passwörter lassen sich jederzeit über neue Umgebungsvariablen oder direkt in der Datenbank ändern (`admins`-Tabelle).

## Troubleshooting

- **500-Fehler direkt nach Upload:** Prüfe, ob die PHP-Version ausreichend hoch ist und die benötigten Erweiterungen aktiv sind.
- **Upload funktioniert nicht:** Stelle sicher, dass `static/uploads/` für den Webserver beschreibbar ist.
- **Login nicht möglich:** Stelle sicher, dass beim Installer ein Administrator angelegt wurde. Bei Bedarf lösche `cms.db` (Achtung: Inhalte gehen verloren) und führe `/install` erneut aus.

