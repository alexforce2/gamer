<?php
namespace application\modules\about;
use application\core\mvc\MainController;
use application\modules\about\model;
class Controller extends MainController
{
    public $block, $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }
    public function ActionIndex()
    {
    }

    public function ActionPhilosophy()
    {
        $this->view->title = 'Наши убеждения - GS11';
        $this->view->Generate('about/philosophy.tpl.php');
    }
    public function ActionTariff()
    {
        $this->view->title = 'Детальная информация о тарифных планах - GS11';
        $this->view->Generate('about/tariff.tpl.php');
    }

    public function ActionCompany()
    {
        $this->view->title = 'Компания - GS11';
        $this->view->keywords = 'компания GS11, всё о gs11, кто gs11';
        $this->view->description = 'Компания GS11 была создана в 2012 году, тогда она лишь только зарождалась и не представляла из себя всё то, что мы можем представить вам сегодня.';
        $this->view->Generate('about/company.tpl.php');
    }

    public function ActionOffer()
    {
        $this->view->title = 'Оферта — GS11';
        $this->view->Generate('about/offer.tpl.php');
    }
    public function ActionPromo()
    {
        $data['users_winner'] = $this->model->GetLastWinner();
        $data['near_tournaments'] = $this->model->GetLastTournament();
        $data['count_users'] = $this->model->CountUsers();
        $this->AddCss("style");
        $this->AddJs("script");

        $arrFileTpl = array("legend-tournament"=>"/skins/tpl/about/legend-tournament-promo.tpl.php",
        "next-tournament"=>"/skins/tpl/about/next-tournament-promo.tpl.php",
        "winner"=>"/skins/tpl/about/winner-promo.tpl.php");
        if(!empty($_POST['page']))
        {
            include $_SERVER['DOCUMENT_ROOT'].$arrFileTpl[$_POST['page']];
            exit();
        }

        $arrInfoTpl = array(
            "legend-tournament"=> array("Легендарные Off-line турниры","off-line турниры, PC игры, GS11","Компания GS11 дает возможность вам, участвовать в off-line турнирах по всем PC играм.","about/legend-tournament-promo.tpl.php"),
            "next-tournament"=> array("Ближайшие Off-line турниры","Ближайшие туриниы","Следите за предстоящими off-line турнирами.","about/next-tournament-promo.tpl.php"),
            "winner"=> array("Победители Off-line турниров","победители, off-line турниры","Здесь вы можете узнать о победтелях турниров на нашем сайте","about/winner-promo.tpl.php")
        );
        foreach($arrInfoTpl as $key => $value ){
            if($key == $_GET['page']){
                list($this->view->title,$this->view->keywords,
                    $this->view->description, $pageUrl) = $arrInfoTpl[$key];
            }
        }

        $data['error'] = '';
        $this->view->Generate($pageUrl, $data);
    }

    public function ActionGamesForever()
    {
        $data['games_forever'] = $this->model->GetRandomGame();
        $this->view->title = 'Игры навсегда';
        $data['error'] = '';
        $this->view->Generate('about/games-forever.tpl.php', $data);
    }
    public function ActionThanks()
    {
        $data['thanks_info'] = $this->model->GetInfoThanks();
        if(!empty($_POST['id'])){
            foreach($data['thanks_info'] as $key => $value){
                if($value['name_partner'] == $_POST['id']){
                    echo json_encode($data['thanks_info'][$key]);
                }
            }
            exit();
        }
        $this->view->title = 'Благодарности';
        $data['error'] = '';
        $this->view->Generate('about/thanks.tpl.php', $data);
    }
    public function ActionContacts()
    {
        $this->view->title = 'Контакты';
        if($_SERVER['REQUEST_METHOD']=== 'POST')
        {
           if($_SESSION['code-captcha'] == $_POST['code-captcha-input'])
           {
                $this->model->InsertContactMessage($_POST['data'], $_POST['id']);
                echo 1;
                exit();
           }
           echo 0;
           exit();
        }
        $this->view->Generate('about/contacts.tpl.php');
    }


}


