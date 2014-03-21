<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] .'/application/core/config/autoloader.php';
use application\core\config\route;
$objRoute = new Route();
$objRoute->Run();
