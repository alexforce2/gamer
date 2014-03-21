<?php
namespace application\core\mvc;
use classes\OftenFunctions;

class MainView
{
	private $arrJs, $arrCss = "";
    public $title, $description, $keywords, $countQuery;
    static $types_complexity = ['Люблю пройти на лёгком', 'Выбираю нечто среднее', 'Всегда всё самое тяжёлое'];

    public $user = array();
    public function __construct($user = null)
    {
        $this->user = $user;
        $this->rootDir = $_SERVER["DOCUMENT_ROOT"] . "/";
    }


    public function Generate($contentView, $data = null)
    {
        if(is_array($data))
            extract($data);
        $arrCss = $this->RenderCss("render");
        $tplOther = $this->TplOther();
        $tplAuth = (object)$this->TplAuth();
        $arrJs = $this->RenderJs("render");
        $bread = $this->BreadCrumbs(array('/', 'Главная'), array('/old', 'Чуток ниже'), array('/old-s', 'И еще чуток ниже'));
        include $tplAuth->content;
        if(!empty($this->countQuery))
            echo 'Количество запросов: ' . $this->countQuery . '.<br>';
    }

    public function SinglePage($contentView, $data = null)
    {

        if(is_array($data))
            extract($data);
        $arrCss = $this->RenderCss("render");
        $arrJs = $this->RenderJs("render");
        include $this->rootDir . "skins/tpl/" . $contentView;
    }



    private function TplAuth()
    {
        $arr = explode("/", $_SERVER["REQUEST_URI"]);
        if($arr[1] == "administration")
        {
            return array("menu" => $this->rootDir . 'skins/tpl/menu/admin-menu.tpl.php', "content" => $this->rootDir . 'skins/tpl/index-admin.tpl.php');
        }
        return (!empty($_SESSION['auth'])) ?
            array("menu" => $this->rootDir . 'skins/tpl/menu/auth-menu.tpl.php', "content" => $this->rootDir . 'skins/tpl/index-auth.tpl.php') :
            array("menu" => $this->rootDir . 'skins/tpl/menu/main-menu.tpl.php', "content" => $this->rootDir . 'skins/tpl/index.tpl.php');
    }

    private function TplOther()
    {
        return (object)array('likes' => $this->rootDir . 'skins/tpl/block/likes.block.tpl.php');
    }


    public function BreadCrumbs() {
        $arg_list = func_get_args();
        $numArgs = func_num_args();
        $str = '';
        for ($i = 0; $i < $numArgs; $i++) {
            $str .= ($arg_list[$i][1] ?
                ($arg_list[$i][0] ?
                    '<a href="' . $arg_list[$i][0] . '">' . $arg_list[$i][1] . '</a>' :
                    $arg_list[$i][1]
                ) . ($numArgs - 1 > $i && $arg_list[$i][0] ?
                    '<span>›</span>' :
                    ''
                ) : ''
            );
        }

        return $str;
    }

    private function MaxStrWord($text, $countText = 10, $sep = ' ')
    {
        $words = explode($sep, $text);
        if (count($words) > $countText)
            $text = join($sep, array_slice($words, 0, $countText));
        return $text;
    }

    private function GetDateRu($param, $data)
    {
        $arrMonth = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля', 5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа', 9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
        $arrWeek = array('Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресение');
        return ($param == 'month') ? $arrMonth[date('n', strtotime($data))] : $arrWeek[date('l', strtotime($data))];
    }
    /*
    private function TrimStr($string, $limit)
    {
        $substring_limited = substr($string, 0, $limit);
        return substr($substring_limited, 0, strrpos($substring_limited, ' '));
    }*/

    public function TrimStr($string, $countChars)
    {
        $countString = strlen($string);
        $str = ($countString <= $countChars) ? strip_tags($string) : strip_tags(substr($string, 0, strpos($string, " ", $countChars)));
        return $str;
    }
    public function ReturnText($param)
    {
        return ( isset($_REQUEST[$param]) ) ? $_REQUEST[$param] : '';
    }

    public function GetHappyBirthday()
    {
        $age = (int)((strtotime(date("d.m.Y")) - (int)$_SESSION['user-data']['birthday']) / 60 / 60 / 24 / 365) ;
        $age = (string)$age;
        $age = $age .' '. OftenFunctions::getCorrectStr($age, 'год', 'года', 'лет');
        return $age;
        // if($age{1} == "1")
        //     $str = " год";
        // else if($age{1} >= "2" && (string)$age{1} <= "4" && $age{0} > "1")
        //     $str = " года";
        // else
        //     $str = " лет";

        // return $age . $str;
    }

    /*
    *   ГОРОД
    */
    public function GetCity(){
        // echo "<pre>";
        // print_r($_SESSION);
        // exit();
        return (string)$_SESSION['user-data']['city'];
    }
    /*
    *   ИГРОВОЙ ОПЫТ
    */
    public function GetGameExp(){
        return (string)$_SESSION['user-data']['game_experience'];
    }
    /*
    *   ЛЮБИМЫЙ ЖАНР
    */
     public function GetLoveGenre(){
        return (string)$_SESSION['user-data']['love_genre'];
    }
    /*
    *   ПРЕДПОЧИТАЕМАЕ СЛОЖНОСТЬ
    */
     public function GetLoveComplexity(){
        return (string)$_SESSION['user-data']['love_complexity'];
    }
    /*
    *   ПРЕДПОЧИТАЕМАЕ СЛОЖНОСТЬ
    */
     public function GetLoveGame(){
        return (string)$_SESSION['user-data']['love_game'];
    }

    public function LinkTournament($game, $id)
    {
        $t = str_replace("'", "", strtolower($game));
        $t = str_replace(":", "", $t);
        $t = str_replace(" ", "-", $t);
        return "?t=".$t."&id=".$id;
    }
	public function RenderJs($fileName)
    {
        if($fileName == "render")
            return $this->arrJs;
        $this->arrJs .= '<script type="text/javascript" src="'.$fileName.'"></script>';
    }
	public function RenderCss($fileName)
    {
        if($fileName == "render")
            return $this->arrCss;
        $this->arrCss .= '<link type="text/css" rel="stylesheet" href="'.$fileName.'"/>';
    }

}
