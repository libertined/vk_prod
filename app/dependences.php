<?php

/**
 * Всякие зависимости, без автолоадера грустно
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 1);
$application = $_SERVER["DOCUMENT_ROOT"].'/app';
$templates =  $_SERVER["DOCUMENT_ROOT"].'/templates';
$config = $_SERVER["DOCUMENT_ROOT"].'/config';
$cacheinstance = $_SERVER["DOCUMENT_ROOT"].'/app/casheinstance';