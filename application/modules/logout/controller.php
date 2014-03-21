<?php
namespace application\modules\logout;
use application\core\mvc\MainController;
class Controller extends MainController
{
    public function ActionIndex()
    {
        session_destroy();
        unset($_SESSION);
        setcookie("hash", "", time() -1, "/");
        setcookie("key", "", time() -1, "/");
        $arrUrl = explode("/", $_SERVER["HTTP_REFERER"]);
        $id = !empty($arrUrl[5]) ? "/".$arrUrl[5] : "";
        $action = !empty($arrUrl[4]) ? $arrUrl[4].$id : "Index";
        $this->Redirect($action, $arrUrl[3]);
    }
}