<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
$autoloadPath = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    http_response_code(500);
    die('Error: Folder "vendor" tidak ditemukan. Pastikan Anda telah meng-upload seluruh folder "vendor" ke server hosting.');
}
require $autoloadPath;

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
