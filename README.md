# Feroxz Mini CMS

Ein leichtgewichtiges Content-Management-System auf PHP-Basis mit moderner Optik, Adminbereich und Unterstützung für Beiträge, Seiten und Mediengalerie.

## Features

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
