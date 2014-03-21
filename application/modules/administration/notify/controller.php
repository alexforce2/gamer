<?php
namespace application\modules\administration\notify;
use application\core\mvc\MainController;


class Controller extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
        $this->AuthAdmin();
    }

    public function ActionIndex()
    {
        $this->view->title = "Notify";
        $this->view->Generate('administration/notify/index.tpl.php', true);

    }

    public function ActionHistory()
    {
        $this->view->title = "Log";
        $data = $this->model->GetNotifyHistory();
        $this->view->Generate('administration/notify/history.tpl.php', $data);
    }

    public function ActionGroup()
    {
        $data = $this->model->GetAllUsersCount();
        $this->view->title = "Group Notify";
        $this->view->Generate('administration/notify/notify-group.tpl.php', $data);
    }
}
