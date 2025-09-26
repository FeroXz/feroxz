# Feroxz Mini CMS

Ein leichtgewichtiges Content-Management-System mit Flask, moderner Optik und einfachem Adminbereich.

## Features

- Öffentliche Startseite mit Kartenlayout für Beiträge und Galerie-Highlight
- Admin-Login mit Session-Verwaltung
- Beiträge im WYSIWYG-Editor verfassen, inklusive Bild-Uploads direkt im Text
- Galerie im Adminbereich pflegen (hochladen, bearbeiten, löschen)
- Moderne Glas-Effekt-Oberfläche optimiert für Desktop und Mobile
- SQLite-Datenbank wird automatisch initialisiert

## Schnellstart

```bash
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
flask --app app run --debug
```

Hochgeladene Medien landen automatisch unter `static/uploads`. Der Ordner wird beim ersten Start erstellt.

Im Adminbereich kannst du unter „Galerie verwalten“ Bilder hinzufügen oder bestehende Einträge anpassen. Beim Bearbeiten von Beiträgen steht ein vollwertiger Editor zur Verfügung; eingebettete Bilder werden über den Upload-Endpunkt automatisch gespeichert.

Standard-Login-Daten kannst du per Umgebungsvariablen anpassen:

```bash
export CMS_ADMIN_USERNAME="deinname"
export CMS_ADMIN_PASSWORD="geheim"
export CMS_SECRET="zufallsstring"
```

## Tests

Zur einfachen Prüfung kann der Code kompiliert werden:

```bash
python -m compileall app.py
```
