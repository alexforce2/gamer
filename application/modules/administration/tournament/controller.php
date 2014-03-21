<?php
namespace application\modules\administration\tournament;
use application\core\mvc\MainController;
use application\modules\administration\tournament\model;
use classes\SimpleImage;
use classes\url;

class Controller extends MainController
{
    public $model, $upload;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->AuthAdmin();
    }

    public function ActionIndex()
    {

        $data = $this->model->ListTournaments();
        $this->view->Generate('administration/tournament/tournaments-list.tpl.php', $data);
    }

    // начало управление турнирами
    public function ActionTournaments()
    {
        $data = $this->model->ListTournaments();
        $this->view->Generate('administration/tournament/tournaments-list.tpl.php', $data);
    }
    public function ActionEditTournament()
    {
        $data['tournament'] = $this->model->GetTournament();
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('administration/tournament/tournament-edit.tpl.php', $data);
    }

    public function ActionCreateTournament()
    {
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('administration/tournament/tournament-edit.tpl.php', $data);
    }

    public function ActionSaveTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if ($this->_p["id"] > 0)
                $res = $this->model->EditTournament($this->_p);
            else
                $res = $this->model->AddTournament($this->_p);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"], "tournaments");
            $this->view->Generate('administration/tournament/tournament-edit.tpl.php', $data);
        }
    }
    // конец управление турнирами


    /***********<<  Начало управления победителями турниров >>***********/

    public function ActionWinnerTournamentList()
    {
        if(!empty($this->_p['id']))
        {
            echo json_encode($this->model->ListWinnerUsers());
            exit();
        }

        $data['list-tournament'] = $this->model->ListWinnerTournament();
        $this->view->Generate('administration/tournament/winner-tournament-list.tpl.php',$data);
    }
    public function ActionWinnerTournamentEdit()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(!empty($this->_p['id'])){
            echo json_encode($this->model->ListAllUsers());
            exit();
        }
        $data['winner'] = $this->model->GetEditInfoWinner();
        $data['list-users'] = $this->model->ListAllUsers();
        $this->view->Generate('administration/tournament/winner-tournament-edit.tpl.php',$data);
    }

   public function ActionSaveWinner()
    {
        $this->model->AddWinner($this->_p);
        $this->Redirect("winner-tournament-list");
    }
    public function ActionDeleteWinner()
    {
        $this->model->DeleteWinner();
        $this->Redirect("winner-tournament-list");
    }
    /*************************Конец управления победителями турниров******************************/
    private function Upload()
    {
        $objImage = new SimpleImage();
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = "i/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            $objImage->load($value['tmp_name'])->square_crop(360)->save($fileName);
        }
    }

    /*public function ActionEditTournament()
    {
        $data['tournament'] = $this->model->GetTournament();
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionCreateTournament()
    {
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionSaveTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->_p["id"] > 0)
                $res = $this->model->EditTournament($this->_p);
            else
                $res = $this->model->AddTournament($this->_p);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"], "tournaments");
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }*/
    // конец победители турниров

    public function ActionCreate()
    {
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "");
        $this->view->Generate('administration/tournament/edit.tpl.php', $data);
    }
}