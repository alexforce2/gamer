<?php
namespace application\modules\search;
use application\core\mvc\MainController as MainController;
use application\modules\search\model as Model;


class Controller extends MainController
{
    public $model;
    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }

    public function ActionIndex($getStr)
    {
        // todo необходимо доработать страницу с поиском
        $this->view->title = 'Поиск в GS11';
        $arrGet = $this->ParseGet($getStr);
        if(isset($arrGet['s']))
            $this->view->title = urldecode($arrGet['s']) . ' - Поиск в GS11';
        $data["name"] = "Вася";
        $data['error'] = '';
        $this->view->Generate('search/index.tpl.php', $data);
    }
}