<?php

// 1. Prepare serverless storage and cache directories in /tmp
$storageDir = '/tmp/storage';
$subDirs = [
    $storageDir.'/app',
    $storageDir.'/framework',
    $storageDir.'/framework/cache',
    $storageDir.'/framework/cache/data',
    $storageDir.'/framework/sessions',
    $storageDir.'/framework/views',
    $storageDir.'/logs',
    '/tmp/bootstrap/cache',
];

foreach ($subDirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Point Laravel storage & cache environment variables to /tmp
$_ENV['LARAVEL_STORAGE_PATH'] = $storageDir;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storageDir;
$_ENV['APP_STORAGE'] = $storageDir;
$_SERVER['APP_STORAGE'] = $storageDir;
$_ENV['VIEW_COMPILED_PATH'] = $storageDir.'/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $storageDir.'/framework/views';

// 3. Forward request to Laravel public/index.php
require __DIR__.'/../public/index.php';
