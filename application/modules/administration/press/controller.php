<?php
namespace application\modules\administration\press;
use application\core\mvc\MainController;
use application\modules\administration\press\model;
use classes\SimpleImage;
use classes\url;

class Controller extends MainController
{
    public $path = "storage/press/news";
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->AuthAdmin();
    }

    public function ActionIndex()
    {
    }

    public function ActionNews()
    {
        $data = $this->model->ListNews();
        $this->view->Generate('administration/press/news.list.tpl.php', $data);
    }

    public function ActionCreateNews()
    {
        $this->PrepareFiles($this->path);
        $data = (object)array("id" => 0, "date" => date("d.m.Y"), "header" => "", "short" => "", "text" => "", "keywords" => "", "description" => "", "title" => "", );
        $this->view->Generate('administration/press/news.edit.tpl.php', $data);
    }

    public function ActionEditNews()
    {
        $this->PrepareFiles($this->path);
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            ($this->_p["id"] > 0) ? $this->model->EditNews() : $this->model->AddNews();
            $this->Redirect("news");
        }
        else
        {
            $data = $this->model->GetNews()[0];
            $this->view->Generate('administration/press/news.edit.tpl.php', $data);
        }
    }

    public function ActionDelete()
    {
        $id = $_REQUEST["id"];
        if ($id > 0)
        {
            $res = $this->model->Delete($id);
            return $res;
        }
        return Route::ErrorPage404();
    }
}