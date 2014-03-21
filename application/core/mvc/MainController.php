<?php
namespace application\core\mvc;
use application\core\mvc\MainModel;
use classes\comments;
use classes\pagination;
use classes\url;
use classes\upload;
use classes\likes;
use PDO;
use Exception;


class MainController
{
    public $_p, $_g = array();
    public $mainModel, $view, $pagination, $model, $comments, $likes;
    public static $storageTemp = "storage/temp";
    public function __construct()
    {
        $this->_p = $_POST;
        $this->_g = $_GET;
        $user = (!empty($_SESSION['user-data'])) ? $_SESSION['user-data'] : null;
        $this->model = new MainModel();
        $this->comments = new Comments();
        $this->likes = new Likes();
        $this->view = new MainView($user);

        if(empty($_SESSION['auth']))
        {
            $this->model->AuthCookie();
        }
        if(!empty($_SESSION['auth']))
        {
            $this->model->GetRefreshDataUser();
        }

    }

    public function RedirectMain()
    {
        if (!$_SESSION['admin']['auth'])
        {
            header("Location: /");
            exit();
        }
    }
    protected function ExistSessionAuth()
    {
        if(isset($_SESSION['auth']) && $_SESSION['auth'] == '1')
            return true;
        header('Location: /');
        exit();
    }
    protected function AccessPageNotAuth()
    {
        if(!empty($_SESSION['user-data']) && !empty($_SESSION['auth']))
        {
            header("Location: /profile");
            exit();
        }
    }

    protected function AuthAdmin()
    {
        if(empty($_SESSION['admin']) && empty($_SESSION['admin']['auth']))
            $this->Redirect("auth", "administration");
    }

    public function Json($data)
    {
        print_r(json_encode($data));
        exit;
    }

    public function Redirect($action, $controller = null)
    {
        header("Location: " . Url::Action($action, $controller));
        exit();
    }
    protected function NavigationPage($sql, $rows, $links)
    {
        $this->pagination = new Pagination($sql, $rows, $links);
        $objResult = $this->pagination->PaginateAdvert();
        $data['obj'] = $objResult->fetchAll(PDO::FETCH_ASSOC);
        $data['navigation'] = $this->pagination->RenderFullNav();
        return $data;
    }


    protected function ParseGet($get)
    {
        $arrParam = explode("&", $get);
        foreach($arrParam as $val)
        {
            $arrVal = explode("=", $val);
            $arrUrl[$arrVal[0]] = $arrVal[1];
        }
        return $arrUrl;
    }
    public function RunAjax()
    {
        if( isset($this->_p['ajax-query']) )
        {
            $method    = $this->_p['method'];
            $typeClass = $this->_p['type-class'];
            try
            {
                $result    = $this->$typeClass->$method();
                if($result) echo $result;
            }
            catch (Exception $e)
            {
                echo 'Произошла ошибка, попробуйте позже';
                error_log('AJAX error: ' . $e->getMessage() . '; Trace: ' . json_encode($e->getTrace()) );
            }
            exit();
        }
    }

    public function PrepareFiles($path)
    {
        if(!empty($_FILES))
        {
            $objUpload = new Upload($path);
            $method = $this->_p['method'];
            $objUpload->$method($path);
            exit();
        }
    }

    public function ExistPage($id)
    {
        if(empty($id) && $id <= 0)
            $this->Redirect("index", "error");
    }

	public function AddJs($file)
    {
        $arrUri = explode("?", $_SERVER['REQUEST_URI']);
        $arrUrl = explode("/", $arrUri[0]);
        $path = ($arrUrl[1] == "administration") ? "/".$arrUrl[1]."/".$arrUrl[2] : "/".$arrUrl[1];
        $name = "/skins/tpl".$path. "/js/". $file.".js";
        $this->view->RenderJs($name);
    }

	public function AddCss($file)
    {
        $arrUri = explode("?", $_SERVER['REQUEST_URI']);
        $arrUrl = explode("/", $arrUri[0]);
        $path = ($arrUrl[1] == "administration") ? "/".$arrUrl[1]."/".$arrUrl[2] : "/".$arrUrl[1];
        $name = "/skins/tpl".$path. "/css/". $file.".css";
        $this->view->RenderCss($name);
    }

}
