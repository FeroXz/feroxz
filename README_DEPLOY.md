# Deployment Guide

## Requirements
- PHP 8.1 with PDO SQLite, Imagick (optional for faster image conversion)
- Node.js 18+ for QA tooling and Lighthouse CI
- Web server (Apache 2.4+ or Nginx 1.20+) with HTTPS enabled

## Build & Asset Pipeline
1. Install dependencies:
   ```bash
   npm install
   ```
2. Configure Git hooks once per clone to auto-bump the version on every push:
   ```bash
   git config core.hooksPath scripts/git-hooks
   ```
2. Generate responsive image derivatives (WebP/AVIF variants under `public/media/generated/`):
   ```bash
   npm run build:images
   ```
3. Run quality gates (requires the application to be served locally on `http://localhost:8080`):
   ```bash
   npm run audit
   ```
   Reports are written to `reports/` (`lighthouse/`, `html-validation.txt`, `schema-validation.json`).

## Environment Variables
- `ASSET_CDN_URL`: Optional CDN base for static assets.
- `FEATURE_GENETICS_TEASER`: Set to `0` to hide the Genetik-Rechner CTA on the homepage.
- `CLOUDFLARE_ZONE_ID` / `CLOUDFLARE_API_TOKEN`: Enable CDN cache purge via `scripts/purge-cache.sh`.

## Web Server Configuration
### Apache (`public/.htaccess` already included)
- Long-term caching for static assets (1 year for images, 7 days for CSS/JS).
- Security headers (`X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`).
- Rewrites to expose `sitemap.xml` and `robots.txt`.
- Consider enabling mod_cache or a reverse proxy with micro-caching (30–120s) for HTML.

### Nginx snippet
```
location / {
    try_files $uri /index.php?$query_string;
}

location ~* \.(css|js)$ {
    expires 7d;
    add_header Cache-Control "public, max-age=604800";
}

location ~* \.(png|jpg|jpeg|gif|webp|avif|svg)$ {
    expires 365d;
    add_header Cache-Control "public, max-age=31536000, immutable";
}

# micro cache for HTML
proxy_cache ferox_micro;
proxy_cache_valid 200 60s;
proxy_cache_use_stale error timeout updating;
```

## Monitoring & Operations
- Health endpoint: `GET /index.php?route=healthz` returns JSON status.
- CDN purge: run `./scripts/purge-cache.sh` after deploy (exports required environment variables).
- Sitemap: `/sitemap.xml` (rewritten to `sitemap.php`) and `robots.txt` are generated automatically at runtime.
- Deploy hook should ping search engines with the sitemap URL (e.g. `curl https://www.google.com/ping?sitemap=https://bartagame.eu/sitemap.xml`).

## Database Migrations
No schema changes in this release. Ensure the SQLite file under `storage/database.sqlite` remains writable by the web server user.
