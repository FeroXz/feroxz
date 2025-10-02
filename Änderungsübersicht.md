# Änderungen

## Performance
- Responsive Picture-Komponente (`render_responsive_picture`) für WebP/AVIF + Lazy-Loading.
- Build-Skript `scripts/generate-responsive-images.php` und `npm run build:images`.
- HTTP-Caching-Regeln via `public/.htaccess` inklusive CDN-Rewrite und Sicherheitsheadern.

## SEO
- Dynamische Meta-/OpenGraph-Tags und Canonicals mit Breadcrumb-Ausgabe pro Seite.
- Strukturierte Daten (Organization, BreadcrumbList, Article, Product, SoftwareApplication) gebündelt im Footer.
- Sitemap-Generator (`public/sitemap.php`) und robots.txt mit automatischer Rewrite-Regel.

## Accessibility
- Skip-Link, verbesserte Fokusdarstellung und `aria`-optimierte Navigation.
- Inhaltsverzeichnis + Lesedauer in Pflegeartikeln.
- Geschlechtsauswahl in allen Tierformularen mit tastaturbedienbaren Icon-Optionen.

## UX & IA
- Vollflächiges Aurora-Layout mit neuem Header, Glas-Navigation und 100%-Viewport-Sektionen auf allen Geräten.
- Startseiten-Hero mit drei zentralen CTAs, Kennzahlen und Vertrauenssektion.
- Vereinheitlichte Tier-, Vermittlungs- und Guide-Ansichten mit responsiven Bildern, Rich-Text-Blöcken und Barrierefreiheit.
- Adminbereich mit optimierter Shell für Desktop und Mobile inklusive breiter Tabellen und Formular-Gitter.

## Infrastruktur & QA
- Health-Endpoint (`/index.php?route=healthz`) für Monitoring.
- QA-Tooling über `npm run audit` (Lighthouse CI, HTML-Validator, Schema-Check) mit Reports-Verzeichnis.
- CDN-Purge-Skript `scripts/purge-cache.sh` und Dokumentation in `README_DEPLOY.md`.

## Neue Routen/Endpunkte
- `/index.php?route=healthz`
- `/sitemap.xml` (Rewrite auf `sitemap.php`)
- `/robots.txt`

## Konfigurationsschalter
- `ASSET_CDN_URL`
- `FEATURE_GENETICS_TEASER`
- `CLOUDFLARE_ZONE_ID` / `CLOUDFLARE_API_TOKEN`
