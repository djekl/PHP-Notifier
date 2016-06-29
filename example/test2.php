<?php

ini_set('display_errors', true);
error_reporting(-1);

date_default_timezone_set('Europe/London');

if (!defined('__DIR__')) {
    define('__DIR__', dirname(__FILE__));
}

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

// load our environment variables
Dotenv::load(__DIR__);
Dotenv::required(['DROPBOX_TOKEN', 'DROPBOX_SECRET']);

$notifier = new MacNotifier(getenv('DROPBOX_TOKEN'), getenv('DROPBOX_SECRET'));

$notifier->readDir();
