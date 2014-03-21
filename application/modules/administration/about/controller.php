<?php
namespace application\modules\administration\about;
use application\core\mvc\MainController;
use application\modules\administration\about\model;
use classes\SimpleImage;
use classes\upload;
use classes\url;



class Controller extends MainController
{
    public  $model,$rootDir;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->AuthAdmin();
    }
    public function ActionIndex()
    {
    }
    public function ActionMessage()
    {
        if(!empty($this->_p['id']))
        {
            $this->model->UpdateContact($this->_p['id']);
            exit();
        }
        $data['message_contact'] =  array_reverse($this->model->GetMessageContact());
        $data['count_message'] = $this->model->CountMessage();
        $this->view->Generate('administration/about/message.list.tpl.php', $data);
    }
    public function ActionGames()
    {
        $data = $this->model->ListGameForever();
        $this->view->title = "";
        $this->view->Generate('administration/about/games.list.tpl.php', $data);
    }
    public function ActionAddGame()
    {

        $this->PrepareFiles(self::$storageTemp);
        if(!empty($this->_p['data']))
        {
            $this->model->SetDataGameForever($this->_p['data']);
            exit();
        }
        $data['games'] = $this->model->ListGames();
        $this->view->Generate('administration/about/games.edit.tpl.php', $data);
    }
    public function ActionEditGame()
    {
        $this->PrepareFiles(self::$storageTemp);
        $data = $this->model->GetDataGameForever()[0];
        if(!empty($this->_p['data']))
        {
            $this->model->EditDataGameForever($this->_p['data'],$this->_p['data'][0]['value']);
            exit();
        }
        $this->view->Generate('administration/about/games.edit.tpl.php', $data);
    }
    public function ActionDeleteGame()
    {
        $id = $this->_g["id"];
        if ($id > 0)
        {
            $this->model->RemoveGameForever($id);
        }
            $this->Redirect("games", "administration.about");
        }
    public function ActionDeleteThanks()
    {
        $id = $this->_g["id"];
        if ($id > 0)
        {
            $this->model->RemoveDataThanks($id);
        }
        $this->Redirect("thanks", "administration.about");
    }


    public function ActionThanks()
    {
        $data = $this->model->ListThanks();
        $this->view->title = "Раздел благодарности";
        $this->view->Generate('administration/about/thanks.list.tpl.php', $data);
    }
    public function ActionEditThanks()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(!empty($this->_p['data']))
        {
            $this->model->EditDataThanks($this->_p['data'],$this->_p['data'][0]['value']);
            exit();
        }

        $data = $this->model->GetDataThanks()[0];
        $this->view->Generate('administration/about/thanks.edit.tpl.php', $data);

    }
    public function ActionAddThanks()
    {

        $this->PrepareFiles(self::$storageTemp);
        if(!empty($this->_p['data']))
        {
            $this->model->SetDataThanks($this->_p['data']);
            exit();
        }
        $this->view->Generate('administration/about/thanks.edit.tpl.php');

    }
}
