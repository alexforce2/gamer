<?php
namespace application\core\config;
class Route
{
    private $arrRoute, $methodExist, $arrRoutePath = array();
    public function __construct()
    {

    }
    public function Run()
    {
        $arrRoutes = $this->ParseUrl($_SERVER['REQUEST_URI']);
        switch ($arrRoutes['name'])
        {
            case '404': $partPath = "error"; break;
            case 'administration': $partPath = "administration\\auth"; break;
            case 'restore': $partPath = "main"; $arrRoutes['action'] = 'Restore'; break;
            default: $partPath = $arrRoutes['name']; break;
        }
        $controllerPath = "application\\modules\\{$partPath}\\controller";
        $controller = new $controllerPath();
        $action = 'Action'.$arrRoutes['action'];
        method_exists ($controller , $action) ? $controller->$action($arrRoutes['param']) : Route::ErrorPage404();
    }

    public static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        //header('Location:'.$host.'404');
        printf("Not Found.");
        exit();
    }



    public function ParseUrl($request_uri)
    {
        $this->arrRoute = parse_url($request_uri);
        $this->arrRoutePath = array_diff(explode('/', $this->arrRoute['path']), array(''));
        $name = $this->GetName();
        $action = $this->GetAction();
        $param = $this->GetParam();
        $fullRoute = array('name' => $name, 'action' => $action, 'param' => $param);
        return $fullRoute;
    }


    private function GetName()
    {
        if(count($this->arrRoutePath) == 1 && $this->arrRoutePath[1] == 'administration')
        {
            $name = 'administration';
        }
        elseif(count($this->arrRoutePath) > 1 && $this->arrRoutePath[1] == 'administration')
        {
            $name = 'administration\\'.$this->arrRoutePath[2];
        }else
        {
            $name = (isset($this->arrRoute['path']) && $this->arrRoute['path'] != '/') ? $this->arrRoutePath[1] : 'main';
        }

        $arrName = explode('-', $name);
        if(count($arrName) == 2)
        {
            $name = $arrName[0];
        }
        return $name;
    }
    private function GetAction()
    {

        foreach(array_reverse($this->arrRoutePath) as $r)
        {
            if($r != "")
                $this->methodExist[] = $r;
        }
        $actionCheck = (int)$this->methodExist[0];
        $action = ( (!empty($this->arrRoutePath) && $this->arrRoutePath[1] == "administration" && count($this->methodExist) > 2)
            || (!empty($this->arrRoutePath) && $this->arrRoutePath[1] != "administration" && count($this->methodExist) > 1) ) ? ucfirst($this->methodExist[0]) : "Index";

        if($actionCheck > 0)
        {
            $action = ucfirst($this->methodExist[1]);
            $actionPrepare = $this->ExplodeRoutePath($this->methodExist[1]);
        }
        else
        {
            $actionPrepare = $this->ExplodeRoutePath($this->methodExist[0]);
        }
        if(!empty($actionPrepare))
            $action = $actionPrepare;
        return $action;
    }

    private function GetParam()
    {
        $this->methodExist = array_reverse($this->arrRoutePath);
        $actionCheck = (!empty($this->methodExist[0])) ? (int)$this->methodExist[0] : 0;
        $param = (isset($this->arrRoute['query'])) ? $this->arrRoute['query'] : "";
        if($actionCheck > 0)
            $param = $actionCheck;
        return $param;
    }

    private function ExplodeRoutePath($method)
    {
        $routePath = explode("-", $method);
        $action = "";
        if(count($routePath) > 1)
        {
            foreach($routePath as $r)
            {
                $action .= ucfirst($r);
            }
        }
        return $action;
    }
}
