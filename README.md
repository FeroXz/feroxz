# Feroxz CMS

Ein leichtgewichtiges PHP-CMS mit SQLite-Backend, das speziell für Shared-Hosting-Umgebungen optimiert wurde. Inhalte, Medien und Genetikdaten werden persistiert auf dem Webspace abgelegt – ein Installer oder zusätzliche Serverdienste sind nicht notwendig.

## Features

- PHP-Frontcontroller (`public/index.php`) mit sessionbasierter Authentifizierung
- CRUD-Workflows für Beiträge, Seiten und Galerieeinträge (inkl. Datei-Uploads)
- Genetik-Datenbank für *Pogona vitticeps* und *Heterodon nasicus* mit erweiterbarer Genverwaltung
- Punnett-Quadrat-Rechner für rezessive, co-dominante und dominante Gene
- Persistente Speicherung in `storage/cms.sqlite` via PDO/SQLite
- Standard-Admin-Login: `admin` / `12345678`

## Anforderungen

| Komponente | Mindestversion |
|------------|----------------|
| PHP        | 8.1+ (getestet mit 8.2/8.3) |
| SQLite     | 3 |

Zusätzlich müssen die PHP-Erweiterungen `pdo_sqlite` und `sqlite3` aktiviert sein. Für Datei-Uploads benötigt das Verzeichnis `public/uploads` Schreibrechte.

## Installation

1. **Dateien hochladen** – Über FTP oder das Hosting-Panel das Repository nach `public_html` (oder einen anderen Webroot) kopieren.
2. **Berechtigungen setzen** – Die Verzeichnisse `storage/` und `public/uploads/` für den Webserver schreibbar machen (z. B. `chmod 775`).
3. **Datenbank wird automatisch erstellt** – Beim ersten Aufruf legt das CMS `storage/cms.sqlite` an, erstellt Tabellen und füllt Demodaten.
4. **Anmelden** – Rufe `/public/index.php?route=login` auf und melde dich mit `admin` / `12345678` an. Ändere das Passwort direkt in der Datenbank oder erweitere den Code um eine Passwort-Änderungs-Funktion.

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
