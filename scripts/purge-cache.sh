#!/usr/bin/env bash
set -euo pipefail

if [[ -z "${CLOUDFLARE_ZONE_ID:-}" || -z "${CLOUDFLARE_API_TOKEN:-}" ]]; then
  echo "Skipping CDN purge: CLOUDFLARE_ZONE_ID or CLOUDFLARE_API_TOKEN not set" >&2
  exit 0
fi

curl -X POST "https://api.cloudflare.com/client/v4/zones/${CLOUDFLARE_ZONE_ID}/purge_cache" \
  -H "Authorization: Bearer ${CLOUDFLARE_API_TOKEN}" \
  -H "Content-Type: application/json" \
  --data '{"purge_everything":true}'
