<?php
function create_care_article(PDO $pdo, array $data): int
{
    $slug = $data['slug'] ?: slugify($data['title']);
    $slug = ensure_unique_slug($pdo, 'care_articles', $slug);
    $stmt = $pdo->prepare('INSERT INTO care_articles(title, slug, summary, content, is_published) VALUES (:title, :slug, :summary, :content, :is_published)');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $slug,
        'summary' => $data['summary'] ?? null,
        'content' => $data['content'],
        'is_published' => !empty($data['is_published']) ? 1 : 0,
    ]);
    return (int)$pdo->lastInsertId();
}

function update_care_article(PDO $pdo, int $id, array $data): void
{
    $slug = $data['slug'] ?: slugify($data['title']);
    $slug = ensure_unique_slug($pdo, 'care_articles', $slug, $id);
    $stmt = $pdo->prepare('UPDATE care_articles SET title = :title, slug = :slug, summary = :summary, content = :content, is_published = :is_published, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $slug,
        'summary' => $data['summary'] ?? null,
        'content' => $data['content'],
        'is_published' => !empty($data['is_published']) ? 1 : 0,
        'id' => $id,
    ]);
}

function delete_care_article(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM care_articles WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function get_care_articles(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM care_articles ORDER BY title ASC')->fetchAll();
}

function get_published_care_articles(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM care_articles WHERE is_published = 1 ORDER BY title ASC')->fetchAll();
}

function get_care_article(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM care_articles WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $article = $stmt->fetch();
    return $article ?: null;
}

function get_care_article_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM care_articles WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $article = $stmt->fetch();
    return $article ?: null;
}

function ensure_default_care_articles(PDO $pdo): void
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM care_articles WHERE slug = :slug');
    $stmt->execute(['slug' => 'pogona-vitticeps']);
    if ($stmt->fetchColumn() > 0) {
        return;
    }

    $summary = 'Kompletter Praxisleitfaden für die artgerechte Haltung, Ernährung, Technik und Gesundheitsvorsorge von Bartagamen (Pogona vitticeps).';
    $content = <<<HTML
<h2>Steckbrief</h2>
<p><strong>Wissenschaftlicher Name:</strong> <em>Pogona vitticeps</em><br>
<strong>Umgangssprache:</strong> Bartagame, Inland-Bearded Dragon<br>
<strong>Herkunft:</strong> Halbwüsten &amp; Trockenwälder im Osten Australiens<br>
<strong>Lebenserwartung:</strong> 10–14 Jahre bei optimaler Haltung</p>

<h2>Terrarium &amp; Technik</h2>
<p>Für adulte Tiere wird ein Mindestmaß von <strong>150 × 80 × 80&nbsp;cm</strong> (L × B × H) empfohlen. Jungtiere profitieren von einer kleineren Aufzuchtbox (z.&nbsp;B. 100 × 60 × 60&nbsp;cm), damit sie Futter leichter finden.</p>
<ul>
    <li><strong>Substrat:</strong> Lehm-Sand-Gemisch (z.&nbsp;B. 60&nbsp;% Spielsand, 40&nbsp;% Lehm) mit unterschiedlichen Schichthöhen. Punktuell Steinflächen für Krallenabrieb.</li>
    <li><strong>Struktur:</strong> Rückwände, Wurzelholz, erhöhte Sonnenplätze, Höhlen. Mindestens drei Temperaturzonen schaffen.</li>
    <li><strong>Luftfeuchte:</strong> 30–40&nbsp;% tagsüber, nachts kurzzeitig auf 50&nbsp;% erhöhen (leichtes Sprühen in einer Ecke).</li>
</ul>

<h3>Beleuchtung &amp; Wärme</h3>
<table>
    <thead>
        <tr><th>Zone</th><th>Temperatur</th><th>Technik</th></tr>
    </thead>
    <tbody>
        <tr><td>Sonnenplatz</td><td>45–50&nbsp;°C</td><td>Halogen-Spot (70–100&nbsp;W) + UV-HQI (z.&nbsp;B. 70&nbsp;W)</td></tr>
        <tr><td>Grundtemperatur</td><td>28–32&nbsp;°C</td><td>Bright Sun, T5-HO Röhre 6500&nbsp;K für Tageslicht</td></tr>
        <tr><td>Kühle Zone</td><td>24–26&nbsp;°C</td><td>schattige Bereiche, Rückzugsmöglichkeiten</td></tr>
        <tr><td>Nacht</td><td>18–22&nbsp;°C</td><td>keine Heizmatten, ggf. Raumheizung</td></tr>
    </tbody>
</table>
<p><strong>UV-B Versorgung:</strong> Hochwertige Mischlicht-/HQI-Lampe (z.&nbsp;B. SolarRaptor/Arcadia) mit Reflektor. Brenndauer 10–12&nbsp;h. Röhren jährlich, HQI alle 12–18&nbsp;Monate tauschen.</p>

<h2>Ernährung</h2>
<p>Bartagamen sind omnivor mit wechselnden Bedürfnissen im Altersverlauf.</p>
<ul>
    <li><strong>Jungtiere (0–6 Monate):</strong> 70&nbsp;% Insekten, 30&nbsp;% Pflanzliches. Täglich mehrere kleine Futterportionen.</li>
    <li><strong>Subadulte (6–12 Monate):</strong> Verhältnis 50&nbsp;/ 50. Insekten auf drei bis vier Fütterungen pro Woche begrenzen.</li>
    <li><strong>Adulte:</strong> 20–30&nbsp;% Insekten, Rest Wildkräuter &amp; Gemüse.</li>
</ul>
<p><strong>Geeignete Insekten:</strong> Heimchen, Grillen, Schaben, Heuschrecken, Soldatenfliegenlarven. Immer gut füttern (<em>gut-loaden</em>) und mit Calcium ohne D3 stauben.</p>
<p><strong>Pflanzliche Komponenten:</strong> Löwenzahn, Wegerich, Golliwoog, Endivie, Zucchini, Kaktusfeige (ohne Dornen). Obst höchstens 1× monatlich als Leckerbissen.</p>
<p><strong>Supplemente:</strong> Reines Calcium bei jeder Insektenfütterung, 1–2× pro Woche ein Kombipräparat mit Vitamin&nbsp;D3 und Spurenelementen.</p>

<h2>Wasser &amp; Hygiene</h2>
<ul>
    <li>Flache Wasserschale täglich frisch befüllen, steht vorzugsweise im kühleren Bereich.</li>
    <li>Feuchte Häutungsbox mit Sphagnum-Moos während Häutungsphasen anbieten.</li>
    <li>Spotreinigung des Substrates täglich, Komplettreinigung alle 6–8&nbsp;Wochen.</li>
</ul>

<h2>Gesundheitsvorsorge</h2>
<p>Mindestens 1× jährlich eine Kotprobe auf Parasiten untersuchen lassen. Achtet auf folgende Warnsignale:</p>
<ul>
    <li>Appetitverlust oder rapide Gewichtsabnahme</li>
    <li>Lethargie, häufiges Sitzen mit geschlossenen Augen</li>
    <li>Schwellungen am Unterkiefer (Hinweis auf Metabolische Knochenerkrankung)</li>
    <li>Atemgeräusche, Nasenausfluss</li>
</ul>
<p>Bei Verdacht sofort reptilienkundigen Tierarzt aufsuchen. Ein digitales Gewichtstagebuch hilft, Trends frühzeitig zu erkennen.</p>

<h2>Sozialverhalten &amp; Handling</h2>
<p>Bartagamen sind Einzelgänger. Dauerhafte Paar- oder Gruppenhaltung führt häufig zu Stress. Sichtkontakte zwischen Terrarien sind möglich, aber Rückzugsmöglichkeiten müssen gewährleistet sein.</p>
<p><strong>Handling:</strong> Tiere nie am Schwanz hochheben, sondern mit beiden Händen von unten stützen. Handling kurz halten (5–10&nbsp;Minuten) und stets mit Wärmequelle in der Nähe.</p>

<h2>Reproduktion &amp; Zuchtplanung</h2>
<p>Die Fortpflanzung sollte erst nach vollständiger Ausreifung (♀ ab 18&nbsp;Monaten, ♂ ab 16&nbsp;Monaten) erfolgen. Vor der Paarung Winterruhe (8–10&nbsp;Wochen bei 16&nbsp;°C) einplanen.</p>
<ol>
    <li><strong>Paarung &amp; Eiablage:</strong> Eiablagebox (30 × 40 × 25&nbsp;cm) mit feuchtem, grabfähigem Substrat bereitstellen.</li>
    <li><strong>Inkubation:</strong> 29–31&nbsp;°C, 60–70&nbsp;% Luftfeuchte. Schlupf nach 55–65&nbsp;Tagen.</li>
    <li><strong>Aufzucht:</strong> Jungtiere einzeln oder in Kleingruppen mit identischer Größe halten, tägliche Gewichtskontrolle.</li>
</ol>

<h2>Checkliste für den Alltag</h2>
<ul>
    <li>Tägliche Funktionskontrolle der Technik (Temperatur, UV-Licht)</li>
    <li>Frisches Wasser &amp; Futter anreichen, Reste entfernen</li>
    <li>Wöchentlich Gewicht und Allgemeinzustand dokumentieren</li>
    <li>Halbjährlich Terrarium gründlich reinigen und Einrichtung prüfen</li>
</ul>

<p>Mit konsequenter Technikpflege, abwechslungsreicher Ernährung und klar strukturierten Terrarien lässt sich die Lebensqualität von <em>Pogona vitticeps</em> nachhaltig sichern.</p>
HTML;

    create_care_article($pdo, [
        'title' => 'Pogona vitticeps – umfassender Pflegeleitfaden',
        'slug' => 'pogona-vitticeps',
        'summary' => $summary,
        'content' => $content,
        'is_published' => 1,
    ]);
}

