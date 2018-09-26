<?php
require_once "config.inc.php";
require __DIR__.'/vendor/autoload.php';
require_once __DIR__ . '/app/Bootstrap/Autoloader.php';

\Bootstrap\Autoloader::instance()->init();
$server = new Server($setting);