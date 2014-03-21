<?php
namespace application\modules\administration\games;
use ___PHPSTORM_HELPERS\object;
use application\core\mvc\MainController;
use application\modules\administration\games\model;
use classes\url;

class Controller extends MainController
{
    private static $storagePath = "storage/guide-games/";
    private $filter = array("year" => "", "month" => "", "day" => "", "page" => 1);
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
        $this->AuthAdmin();
    }

    public function ActionIndex()
    {
        $this->Redirect("search-guide-game", "administration/games");
    }

    /* Начало добавление списка игр в справочник */
    // добавить добавление игры в обзоры
    public function ActionAddMainListGame()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(empty($_GET['action']))
        {
            $tplGames = 'administration/games/list-games.tpl.php';
            $data = $this->model->ListGames();
        }
        else
        {
            if($_GET['action'] == "edit")
            {
                $data['game'] = $this->model->GetGame($_GET['id']);
                $data['difficulty'] = $this->model->GetDifficulty($_GET['id']);
            }
            else
            {
                $data["game"] = (object)array("id" => false, "genre_id"=> "", "name" => "", "source_img_s" => "", "source_img_b" => "");
            }
            $data['genre'] = $this->model->ListGenre();
            $tplGames = 'administration/games/edit-games.tpl.php';
        }
        $this->view->Generate($tplGames, $data);
    }


    public function ActionEditMainGame()
    {
        if(!empty($this->_p['name']))
        {
            ($_POST['id'] > 0) ? $this->model->UpdateMainGame() : $this->model->AddMainGame();
            $this->Redirect("add-main-list-game", "administration.games");
        }
        else
        {
            echo "Заполните поле имя";
        }
    }
    /* Конец добавление списка игр в справочник */

    /* Начало основные игро-обзоры */
    public function ActionSearchGuideGame()
    {
        $this->view->Generate('administration/games/search-guide-game.tpl.php');
    }

    public function ActionGuideGame($id)
    {
        if(isset($id) && $id > 0){
            $data['game'] = $this->model->GetGame($id);
            $data['rubrics'] = $this->model->GetGameRubrics($id);
            $this->view->Generate('administration/games/guide-game.tpl.php', $data);
        }else {
            echo "Игра не найдена";
        }

    }

    public function ActionGameRubricArticles($id = null)
    {
        $id=(int)$id;
        if(!empty($id)){
            if(!empty($this->_p) && $this->_p['id']>0){
                $this->model->EditGameRubricArticle($this->_p);
                $this->Redirect("game-rubric-articles/".$id, "administration.games");
            }
            $data["rows"] = ($id==false) ? $this->model->GetRubricArticles($id) : $this->model->GetRubricArticles($id);
            $data["game-rubric"] = ($id==false) ? $this->model->GetGameRubricInfo($id) : $this->model->GetGameRubricInfo($id);
            $this->view->Generate('administration/games/game-rubric-articles.tpl.php', $data);
        }elseif($this->_p['id']==="0"){
            $this->model->AddRubricArticle($this->_p);
            $this->Redirect("game-rubric-articles/".$this->_p['id_rubric'], "administration.games");
            //$data["rows"] = $this->model->GetRubricArticles($this->_p['id_rubric']);
            //$data["game-rubric"] = $this->model->GetGameRubricInfo($this->_p['id_rubric']);
            //$this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/game-rubric-articles.tpl.php', '', 'index-admin.tpl.php', $data);
        }else{
            echo "404 - Not found";
        }

    }

    public function ActionEditGameRubricArticle()
    {
        $this->PrepareFiles("storage/guide-games/".$_GET['id-game']);
        $data = $this->model->GetGameRubricArticleInfo($this->_g['id']);
        $data['game-rubric']['id'] = $data['id_game'];
        $this->view->Generate('administration/games/edit-game-rubric-article.tpl.php', $data);
    }

    public function ActionDeleteRubricArticle() //
    {
        if(isset($this->_g['id-article'])){
            $this->model->DeleteRubricArticle($this->_g['id-article']);
            $this->Redirect("game-rubric-articles/".$this->_g['id'], "administration.games");
        }

    }

    public function ActionCreateRubricArticle($id)
    {
        $this->PrepareFiles("storage/guide-games/".$id);
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "keywords" => "", "description" => "", "title" => "", "text" => "");
        $data['game-rubric'] = $this->model->GetGameRubricInfo($id);
        $data["id_mpg_rubric"] = $data['game-rubric']['id_rubric'];
        $this->view->Generate('administration/games/edit-game-rubric-article.tpl.php', $data);
    }
    /* Конец основные игро-обзоры */

    public function ActionMainPage($id)
    {
        if(!empty($this->_p['id-game']))
        {
            $game = $this->model->GetGame($this->_p['id-game']);
            // todo добавить проверку на not found
            if($this->_p['id-game']>0)
            {
                $this->model->EditMainPageGame($game, $id);
            }
            $this->Redirect("index");
        }
        // todo: Добавить проверку на игру
        if(isset($id))
        {
            $this->PrepareFiles("storage/guide-games/".$_GET['id']);
            $data['game'] = $this->model->GetGame($id);
            $data['main-page'] = $this->model->GetMainPageGame($id);
            if(empty($data["main-page"]))
            {
                $data["main-page"] = (object)array("date_release_world" => strtotime(date("d.m.Y")),
                    "date_release_russia" => strtotime(date("d.m.Y")),
                    "publisher" => "",
                    "publisher_link" => "",
                    "publisher_russia" => "",
                    "publisher_russia_link" => "",
                    "developer" => "",
                    "developer_link" => "",
                    "official_site" => "",
                    "official_site_link" => "",
                    "game_mode" => "",
                    "game_engine" => "",
                    "distribution" => "",
                    "sr_os" => "",
                    "sr_cpu" => "",
                    "sr_ram" => "",
                    "sr_video" => "",
                    "sr_hdd" => "",
                    "short" => "",
                    "text" => "",
                    "title" => "",
                    "description" => "",
                    "video_link" => "",
                    "keywords" => "");
            }
            $data['rubrics'] =  $this->model->GetGameRubrics($id);
            $data['screenshot'] =  $this->model->GetMainPageScreenshot($id);
            $data['screenshot-count'] = 6;
            $this->view->Generate('administration/games/main-page.tpl.php', $data);
        }

    }
}