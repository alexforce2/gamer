<?php
namespace application\modules\error;
use application\core\mvc\MainController;
class Controller extends MainController
{
    public function ActionIndex()
    {
        $this->view->SinglePage('404.tpl.php');
    }
}