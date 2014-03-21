<?php
namespace application\modules\administration\logout;
use application\core\mvc\MainController;
class Controller extends MainController
{
    public function ActionIndex()
    {
        session_destroy();
        unset($_SESSION['admin']);
        $this->Redirect("auth", "administration");
    }
}