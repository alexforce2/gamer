<?php
$start = microtime(true);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('UTC');
error_reporting(2047);
ini_set('display_errors', 0);
require_once 'application/bootstrap.php';
//echo 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.<br />';
