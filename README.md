# Feroxz Mini CMS

Ein leichtgewichtiges Content-Management-System mit Flask, moderner Optik und einfachem Adminbereich.

## Features

- Öffentliche Startseite mit Kartenlayout für Beiträge
- Admin-Login mit Session-Verwaltung
- Beiträge erstellen, bearbeiten und löschen
- Moderne Glas-Effekt-Oberfläche optimiert für Desktop und Mobile
- SQLite-Datenbank wird automatisch initialisiert

## Schnellstart

```bash
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
flask --app app run --debug
```

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
