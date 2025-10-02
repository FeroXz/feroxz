<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$versionFile = $root . DIRECTORY_SEPARATOR . 'VERSION';
$readmeFile = $root . DIRECTORY_SEPARATOR . 'README.md';
$date = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$timestamp = $date->format('Y.m.d-His');
$hash = trim(shell_exec('git rev-parse --short HEAD 2>/dev/null'));
if ($hash === '') {
    $hash = 'nogit';
}
$version = $timestamp . '+' . $hash;
file_put_contents($versionFile, $version . PHP_EOL);
if (!is_readable($readmeFile)) {
    throw new RuntimeException('README.md is missing.');
}
$readme = file_get_contents($readmeFile);
$pattern = '/(<!--VERSION-START-->)(.*?)(<!--VERSION-END-->)/s';
$replacement = '$1' . PHP_EOL . $version . PHP_EOL . '$3';
$updatedReadme = preg_replace($pattern, $replacement, $readme, 1, $count);
if ($count === 0) {
    throw new RuntimeException('README.md does not contain version markers.');
}
file_put_contents($readmeFile, $updatedReadme);
