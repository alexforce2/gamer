<?php
namespace application\modules\administration\auth;
use application\core\mvc\MainController;
use application\modules\administration\auth\model;


class Controller extends MainController
{    
    public $model;
    public function __construct()
    {   
        parent::__construct();          
        $this->model = new Model();
        $this->view->title = 'Админ центр — GS11';
    }
	
    public function ActionIndex()
    {
        $this->ExistUrlAuth();
        $this->AddCss("style");
        if(isset($this->_p['auth']))
        {
            $_SESSION['admin'] = $this->model->Auth();
            if(isset($_SESSION['admin']))
               $_SESSION['admin']['auth'] = '1';
            $this->AuthRedirect();
        }
        $this->view->SinglePage('administration/auth/index.tpl.php');
    }

    private function ExistUrlAuth()
    {
        $arrUri = explode("/", $_SERVER["REQUEST_URI"]);
        if($arrUri[2] != "auth")
            $this->Redirect("auth", "administration");
    }

    private function AuthRedirect()
    {
        if(isset($_SESSION['admin']['auth']) && $_SESSION['admin']['auth'] == '1')
            $this->Redirect("main", "administration");
    }
}
