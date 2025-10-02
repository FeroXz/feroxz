import { promises as fs } from 'fs';
import fetch from 'node-fetch';

const baseUrl = process.env.SCHEMA_BASE_URL || 'http://localhost:8080';
const paths = ['/', '/index.php?route=adoption', '/index.php?route=care-guide', '/index.php?route=news'];
const report = [];

for (const path of paths) {
  const url = `${baseUrl}${path}`;
  try {
    const response = await fetch(url);
    const html = await response.text();
    const matches = [...html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)];
    const parsed = matches.map(([, json]) => JSON.parse(json));
    report.push({ url, schemaCount: parsed.length, ok: true });
  } catch (error) {
    report.push({ url, ok: false, error: error.message });
  }
}

await fs.mkdir('reports', { recursive: true });
await fs.writeFile('reports/schema-validation.json', JSON.stringify(report, null, 2));
console.log('Schema validation report written to reports/schema-validation.json');
