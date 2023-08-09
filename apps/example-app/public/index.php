<?php

require_once(__DIR__ . '/../../../src/Rendala/Core.php');

$_SERVER['APP_NAME'] = 'example-app';
$_SERVER['APP_ENV'] = 'dev';

\Rendala\Core::runWeb($_SERVER['APP_NAME'], $_SERVER['APP_ENV']);