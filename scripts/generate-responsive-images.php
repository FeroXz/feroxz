<?php
require_once __DIR__ . '/../app/site.php';
require_once __DIR__ . '/../app/helpers.php';

$sourceDir = realpath(__DIR__ . '/../uploads');
if ($sourceDir === false) {
    fwrite(STDERR, "Upload-Verzeichnis nicht gefunden.\n");
    exit(1);
}

$breakpoints = [480, 768, 1024, 1600];
$formats = ['webp', 'avif'];

function ensurePath(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

function convertImage(string $source, string $destination, int $width, string $format): void
{
    if (class_exists('Imagick')) {
        $image = new Imagick($source);
        $image->setImageFormat($format);
        $image->setImageCompressionQuality(82);
        $image->resizeImage($width, 0, Imagick::FILTER_LANCZOS, 1, true);
        $image->writeImage($destination);
        $image->destroy();
        return;
    }

    $info = getimagesize($source);
    if (!$info) {
        throw new RuntimeException('Bildinformationen konnten nicht gelesen werden: ' . $source);
    }

    $createFunc = match ($info[2]) {
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_GIF => 'imagecreatefromgif',
        default => null,
    };

    if (!$createFunc || !function_exists($createFunc)) {
        copy($source, $destination);
        return;
    }

    $image = $createFunc($source);
    $height = (int)floor(imagesy($image) * ($width / imagesx($image)));
    $canvas = imagescale($image, $width, $height, IMG_BICUBIC_FIXED);

    if ($format === 'webp' && function_exists('imagewebp')) {
        imagewebp($canvas, $destination, 82);
    } elseif ($format === 'avif' && function_exists('imageavif')) {
        imageavif($canvas, $destination, 45);
    } else {
        imagejpeg($canvas, $destination, 82);
    }

    imagedestroy($canvas);
    imagedestroy($image);
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir));
foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }
    $ext = strtolower($file->getExtension());
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
        continue;
    }

    $relativePath = trim(str_replace($sourceDir, '', $file->getPathname()), DIRECTORY_SEPARATOR);
    $baseName = pathinfo($relativePath, PATHINFO_FILENAME);
    $subDir = 'media/generated/' . dirname($relativePath);
    $subDir = rtrim($subDir, './');
    if ($subDir === 'media/generated') {
        $targetPath = __DIR__ . '/../public/' . $subDir;
    } else {
        $targetPath = __DIR__ . '/../public/' . $subDir;
    }
    ensurePath($targetPath);

    foreach ($breakpoints as $width) {
        foreach ($formats as $format) {
            $outputFile = $targetPath . '/' . $baseName . '_' . $width . '.' . $format;
            try {
                convertImage($file->getPathname(), $outputFile, $width, $format);
                echo "Erzeugt: {$outputFile}\n";
            } catch (Throwable $e) {
                fwrite(STDERR, 'Fehler bei ' . $outputFile . ': ' . $e->getMessage() . "\n");
            }
        }
    }
}
