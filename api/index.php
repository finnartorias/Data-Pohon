<?php

// 1. Siapkan direktori penyimpanan yang dapat ditulis di lingkungan Vercel Serverless (/tmp)
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/app/public',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Salin database SQLite yang sudah terisi data pohon ke /tmp jika belum ada
$tmpDb = '/tmp/database.sqlite';
if (!file_exists($tmpDb)) {
    $seededDb = __DIR__ . '/../database/database.sqlite';
    if (file_exists($seededDb) && filesize($seededDb) > 0) {
        copy($seededDb, $tmpDb);
    } else {
        touch($tmpDb);
    }
}

// 3. Jalankan entry point Laravel
require __DIR__ . '/../public/index.php';
