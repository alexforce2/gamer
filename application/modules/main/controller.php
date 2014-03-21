<?php
namespace application\modules\main;
use application\core\mvc\MainController as MainController;
use application\modules\main\model as Model;
use classes\captcha as Captcha;


class Controller extends MainController
{    
    public $block, $model, $sms;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
        $this->AccessPageNotAuth();
    }

    public function ActionIndex()
    {
        $this->view->title = 'GS11';
        $this->view->description = 'Принимайте участие в турнирах по легендарным играм, выигрывая крупные денежные призы, ведите и читайте личные дневники о пройденных играх, а также многое другое мирового гейминга.';
        $this->view->keywords = 'GS11, ГС11, социальная сеть, иговые блоги, игровые турниры';
        $this->view->Generate('main/show.tpl.php');
    }

    public function ActionRestore($getStr)
    {
        $data['error'] = '';
        $this->view->title = 'Восстановление аккаунта — GS11';
        if(!empty($getStr))
        {
            $arrGet = $this->ParseGet($getStr);
            if (isset($arrGet['key']) && isset($arrGet['hash']))
            {
                $_SESSION['restore-key'] = $arrGet['key'];
                $_SESSION['restore-hash'] = $arrGet['hash'];
                $_SESSION['restore-id-user'] = $this->model->ExistEmailRestore($arrGet);
                $data['exist-pass-restore'] = ($_SESSION['restore-id-user']) ? true : false;
            }
        }
        else if(isset($_SESSION['session-restore']) && $_SESSION['session-restore'] == true)
        {
            unset($_SESSION['session-restore']);
            $_SESSION['exist-pass-restore-phone'] = true;
            $data['exist-pass-restore'] = true;
        }
        $this->view->Generate('main/restore.tpl.php', $data);
    }
    public function ActionCaptcha()
    {
        // todo сломалась капча решение(всё зависит от обработчика ошибок, когда он включён с капчей проблемы) по фиксить
        Captcha::Init();
        exit();
    }
}