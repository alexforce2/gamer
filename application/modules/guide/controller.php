<?php
namespace application\modules\guide;
use application\core\mvc\MainController;
use application\modules\guide\model;

class Controller extends MainController
{
    public $block, $model;
    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
    }

    public function ActionIndex()
    {

    }

    public function ActionGames($id)
    {
        if(isset($id) && $id > 0)
        {
            $data['obj'] = $this->model->GetGame($id);
            $data['obj-img'] = $this->model->GetGameImg($data['obj']->id_game);
            $data['obj-rubric'] = $this->model->GetGameRubric($data['obj']->id_game);
            $this->view->title = $data['obj']->name;
            $this->view->description = $data['obj']->description;
            $this->view->keywords = $data['obj']->keywords;
            $this->view->Generate('guide/obj-game.tpl.php', $data);
        }
        else
        {
            $this->view->title = "Обзоры, гайды, вики игр  - GS11";
            $data['games'] = $this->model->ListGames();
            $this->view->Generate('guide/games.tpl.php', $data);
        }
    }
}