<?php
use application\core\config\route;
class AutoLoader
{
    public function Register()
    {
        spl_autoload_register(array($this, 'AutoLoad'));
    }
    public function AutoLoad($className)
    {
        $controllerPath = $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('\\', '/', $className) . '.php';
        (file_exists($controllerPath)) ? include_once($controllerPath) : Route::ErrorPage404();
        return;
    }
}
$autoLoader = new AutoLoader();
$autoLoader->Register();
