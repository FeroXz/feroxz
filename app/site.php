<?php
const SITE_NAME = 'FeroxZ Reptile Center';
const SITE_DOMAIN = 'bartagame.eu';
const PRIMARY_TOPIC = 'Bartagamen, Reptilienhaltung, Genetik, Tierabgabe';
const PRIMARY_LANGUAGE = 'de';
const CONTACT_EMAIL = 'info@bartagame.eu';
const ORG_NAME = 'FeroxZ Reptile Center';
const ORG_LOGO_URL = 'https://bartagame.eu/static/logo.png';
const ORG_SAME_AS = [
    'https://www.instagram.com/...',
    'https://www.facebook.com/...',
];

if (!defined('APP_VERSION')) {
    $appVersion = 'dev-main';
    $versionFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'VERSION';
    if (is_readable($versionFile)) {
        $version = trim((string) file_get_contents($versionFile));
        if ($version !== '') {
            $appVersion = $version;
        }
    }
    define('APP_VERSION', $appVersion);
}
