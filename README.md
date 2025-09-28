# Feroxz CMS

Ein leichtgewichtiges PHP-CMS mit SQLite-Backend, das speziell für Shared-Hosting-Umgebungen optimiert wurde. Inhalte, Medien und Genetikdaten werden persistiert auf dem Webspace abgelegt – ein Installer oder zusätzliche Serverdienste sind nicht notwendig.

## Features

- PHP-Frontcontroller (`public/index.php`) mit sessionbasierter Authentifizierung
- CRUD-Workflows für Beiträge, Seiten und Galerieeinträge (inkl. Datei-Uploads)
- Hierarchische Seitenstruktur mit Menüverwaltung (Sortierung, Sichtbarkeit, Unterseiten) direkt im Adminbereich
- Benutzerverwaltung mit Rollen (Administrator/Editor), modulbezogenen Berechtigungen und Passwortwechsel
- Genetik-Datenbank für *Pogona vitticeps* und *Heterodon nasicus* mit ausführlichen Genbeschreibungen
- Tierverwaltung inklusive Alter, Herkunft, Notizen und Bilder – Tiere können direkt im Rechner ausgewählt werden
- Öffentliche Tierübersicht mit kuratierten Showcase-Karten für ausgewählte Tiere
- Punnett-Quadrat-Rechner für rezessive, co-dominante und dominante Gene mit MorphMarket-ähnlicher Ausgabe
- Persistente Speicherung in `storage/cms.sqlite` via PDO/SQLite
- Standard-Admin-Login: `admin` / `12345678`

## Anforderungen

| Komponente | Mindestversion |
|------------|----------------|
| PHP        | 8.3+ |
| SQLite     | 3 |

Zusätzlich müssen die PHP-Erweiterungen `pdo_sqlite` und `sqlite3` aktiviert sein. Für Datei-Uploads benötigt das Verzeichnis `public/uploads` Schreibrechte.

## Installation

1. **Dateien hochladen** – Über FTP oder das Hosting-Panel das Repository nach `public_html` (oder einen anderen Webroot) kopieren.
2. **Berechtigungen setzen** – Die Verzeichnisse `storage/` und `public/uploads/` für den Webserver schreibbar machen (z. B. `chmod 775`).
3. **Erster Aufruf** – Beim ersten Aufruf legt das CMS `storage/cms.sqlite` an, erstellt Tabellen und füllt Demodaten.
4. **Anmelden & Benutzer verwalten** – Melde dich mit `admin` / `12345678` an und lege im Menü „Benutzer“ bei Bedarf zusätzliche Accounts oder neue Passwörter an.
5. **Seiten- & Menüstruktur anpassen** – Unter „Seiten & Menü“ Seiten anlegen, Unterseiten verschachteln, Reihenfolge und Sichtbarkeit des Navigationsmenüs steuern.
6. **Genetik & Tiere pflegen** – Im Admin-Bereich unter „Genetik“ Gene/Arten verwalten und unter „Tiere“ eigene Tiere mit Genetikzuordnung erfassen. Diese können im Genetik-Rechner direkt ausgewählt werden.

## Struktur

```
public/
├── index.php          # Frontcontroller & Router
├── styles.css         # Basis-Styles
├── uploads/           # Upload-Ziel (per .gitignore leer)
└── views/             # Öffentliche & Admin-Templates
storage/
└── cms.sqlite         # SQLite-Datenbank (wird automatisch erzeugt)
```

## Backups & Updates

- Sichere regelmäßig `storage/cms.sqlite` sowie den Upload-Ordner.
- Bei Updates die neuen Dateien hochladen und bestehende `storage/` & `uploads/` behalten.
- Vor Codeänderungen ein Backup erstellen.

## Lokale Entwicklung

1. Lokalen PHP-Server starten:
   ```bash
   php -S localhost:8000 -t public
   ```
2. Applikation im Browser unter `http://localhost:8000/index.php` öffnen.

## Tests

Syntax-Checks können mit `php -l` durchgeführt werden, z. B.:

```bash
php -l public/index.php
find public/views -name '*.php' -print0 | xargs -0 -n1 php -l
```

Damit wird sichergestellt, dass alle PHP-Dateien fehlerfrei sind.
