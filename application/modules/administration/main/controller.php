<?php
namespace application\modules\administration\main;
use application\core\mvc\MainController;


class Controller extends MainController
{
    public function ActionIndex()
    {
        $this->AuthAdmin();
        $this->view->Generate('administration/main/index.tpl.php');
    }
}
