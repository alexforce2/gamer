<?php
namespace application\modules\base;
use application\core\mvc\MainController;
use application\modules\base\model;
use classes\SimpleImage;
use classes\likes;


class Controller extends MainController
{
    public $block, $model, $sms;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->ExistSessionAuth();
        $this->RunAjax();
    }

    public function ActionIndex()
    {
        $this->AddJs("ajax");
        $this->AddCss("style");
        $data['check-user-id'] = (int)$_SESSION['user-data']['id'];
        $data['user-completed-games'] = $this->model->GetUserCompletedGames();
        $data['type-complete-game'] = $this->model->GetTypeCompleteGame();
        $this->view->title = "Пройденные игры - GS11";
        $this->view->Generate('base/add-completed-games.tpl.php', $data);
    }

    public function ActionView($idGame)
    {
        if($idGame){
            $idUser = (int)$this->_g['iduser'];
            $data = $this->model->GetGameView($idGame, $idUser);
            $data["likes"] = $this->likes->Init(2, $data['id_ucg']);
            $this->view->title = "$data[game] - GS11";
            $this->view->Generate('base/game-view.tpl.php', $data);
        }
    }

    public function ActionEdit($idGame)
    {
        $this->PrepareFiles(self::$storageTemp);
        if($idGame)
        {
            $this->AddJs("ajax");
            $data = $this->model->GetGameView($idGame);
            $data['userGameImg'] = $this->model->GetUserImgGame($idGame);
            $data['levelsArray'] = $this->model->GetLevels($idGame);
            $data['typesCompletedGameArray'] = $this->model->GetTypeCompleteGame();
            $this->view->title = "$data[game] Редактировать - GS11";
            $this->view->Generate('base/game-edit.tpl.php', $data);
        }
    }

    public function ActionSaveChanges()
    {
        $this->model->UpdateAddedGame();
        $this->model->UploadUserGameImg();
        $this->model->RemoveUserImgGame($this->_p['deletedImg']);
        $this->view->Generate('base/game-chanched.tpl.php');
    }

    public function ActionUsers()
    {
        $data['users-completed-game'] = $this->model->GetUsersCompletedGame();
        $this->view->Generate('base/users.tpl.php', $data);
    }

    public function ActionUserGames($idUser)
    {
        $this->AddCss("style");
        $data['user-completed-games'] = $this->model->GetUserCompletedGames($idUser);
        $this->view->title = "Игры пользователя {$data['user-completed-games'][0]['nick']} - GS11";
        $this->view->Generate('base/add-completed-games.tpl.php', $data);
    }



}