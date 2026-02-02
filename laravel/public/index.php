<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode
if (file_exists(__DIR__.'/../laravel/storage/framework/maintenance.php')) {
    require __DIR__.'/../laravel/storage/framework/maintenance.php';
}

// Composer autoload
require __DIR__.'/../laravel/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';

$app->handleRequest(Request::capture());