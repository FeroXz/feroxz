<?php
require_once __DIR__ . '/../app/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$urls = [];
$now = date('c');
$urls[] = ['loc' => canonical_url('/'), 'lastmod' => $now, 'changefreq' => 'daily'];
$urls[] = ['loc' => canonical_url('/index.php?route=animals'), 'changefreq' => 'weekly'];
$urls[] = ['loc' => canonical_url('/index.php?route=adoption'), 'changefreq' => 'daily'];
$urls[] = ['loc' => canonical_url('/index.php?route=care-guide'), 'changefreq' => 'weekly'];
$urls[] = ['loc' => canonical_url('/index.php?route=genetics'), 'changefreq' => 'weekly'];
$urls[] = ['loc' => canonical_url('/index.php?route=news'), 'changefreq' => 'weekly'];

foreach (get_public_animals($pdo) as $animal) {
    $urls[] = ['loc' => canonical_url('/index.php?route=animals') . '#animal-' . $animal['id'], 'changefreq' => 'monthly'];
}
foreach (get_public_listings($pdo) as $listing) {
    $urls[] = ['loc' => canonical_url('/index.php?route=adoption') . '#listing-' . $listing['id'], 'changefreq' => 'daily'];
}
foreach (get_published_care_articles($pdo) as $article) {
    $urls[] = ['loc' => canonical_url('/index.php?route=care-article&slug=' . urlencode($article['slug'])), 'changefreq' => 'monthly'];
}
foreach (get_published_news($pdo) as $post) {
    $urls[] = ['loc' => canonical_url('/index.php?route=news&slug=' . urlencode($post['slug'])), 'changefreq' => 'weekly'];
}
foreach (get_navigation_pages($pdo) as $page) {
    $urls[] = ['loc' => canonical_url('/index.php?route=page&slug=' . urlencode($page['slug'])), 'changefreq' => 'monthly'];
    foreach ($page['children'] ?? [] as $child) {
        $urls[] = ['loc' => canonical_url('/index.php?route=page&slug=' . urlencode($child['slug'])), 'changefreq' => 'monthly'];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '\n';
echo '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
foreach ($urls as $entry) {
    echo '\n  <url>';
    echo '\n    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1) . '</loc>';
    if (!empty($entry['lastmod'])) {
        echo '\n    <lastmod>' . htmlspecialchars($entry['lastmod'], ENT_XML1) . '</lastmod>';
    }
    if (!empty($entry['changefreq'])) {
        echo '\n    <changefreq>' . htmlspecialchars($entry['changefreq'], ENT_XML1) . '</changefreq>';
    }
    echo '\n  </url>';
}
echo '\n</urlset>';
