<?php
namespace classes; //
use application\core\config\route;

class Url
{

    public static function Action($actionName, $controllerName = null)
    {
        if (!$controllerName) {
            $objRoute = new Route();
            $uri_data = $objRoute->ParseUrl($_SERVER['REQUEST_URI']);
            $controllerName = str_replace('\\', '/', $uri_data["name"]);
        }
        return self::RootApp() . str_replace('.', '/', $controllerName) . "/" . $actionName . "/";
    }

    public static function RootApp()
    {
        //todo: сделать правильный
        return "/";
    }
}