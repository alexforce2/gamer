<?php
namespace application\modules\about;
use application\core\mvc\MainModel;
use PDO;


class Model extends MainModel
{
    function __construct()
    {
        parent::__construct();

    }

    public function InsertAboutMessage()
    {
        $query = $this->conn->dbh->prepare("INSERT INTO main_about_msg SET user_id = ?, msg = ?");
        $query->execute(array($_SESSION['user-data']['id'], $this->_p['message']));
    }
    public function GetLastTournament()
    {
        $stmt = $this->conn->dbh->prepare("SELECT t.title, t.pay, t.start_date,g.source_img_s FROM tournaments t LEFT JOIN games g ON t.id_game = g.id
        ORDER BY t.id DESC LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function GetLastWinner()
    {
        $stmt = $this->conn->dbh->prepare("SELECT u.img_avatar, u.first_name, u.last_name, u.nick
         FROM info_winner i  LEFT JOIN users u ON u.id = i.id_user ORDER BY i.id DESC LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function CountUsers()
    {
        $stmt = $this->conn->dbh->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
    public function GetRandomGame()
    {
        $stmt = $this->conn->dbh->prepare("SELECT name_game, source_img, description_game, link_game_anchor,
         link_game FROM games_forever ORDER BY RAND() LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function GetInfoThanks()
    {
        $stmt = $this->conn->dbh->prepare("SELECT name_partner, source_img, link_anchor,link,text FROM thanks");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function InsertContactMessage($params,$id)
    {

            $query = $this->conn->dbh->prepare("INSERT INTO message_contact SET id_rubric = :id_rubric,id_user = :id_user,name_user =:name_user,
            email= :email, text = :text");

            $arr = array(':name_user',':email',':text');
            if(count($arr) > count($params)){
                $newArr = array_reverse($arr);
                for($i = 0; $i < count($newArr); $i++){
                    $query->bindParam("$newArr[$i]",$params[$i]['value'],PDO::PARAM_STR);
                }
            }else{
                foreach($params as $key => $value){
                    $query->bindParam("$arr[$key]",$value['value'],PDO::PARAM_STR);
                }
            }
            $query->bindParam(":id_user",$_SESSION['user-data']['id'],PDO::PARAM_INT);
            $query->bindParam(":id_rubric",$id,PDO::PARAM_INT);
            $query->execute();




    }


}
