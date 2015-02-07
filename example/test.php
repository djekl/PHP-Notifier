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
Dotenv::required(['APP_KEY', 'APP_NAME']);

$notifier = new MacNotifier(getenv('APP_KEY'), getenv('APP_NAME'));

// $notifier->notify($title, $text, $url, $icon);
$notifier->notify('Oh, Hey!', 'Just a friendly greeting', 'https://alanwynn.me', 'com.apple.Automator');
