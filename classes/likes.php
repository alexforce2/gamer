<?php
namespace classes;
use application\core\config\config;
use PDO;

class Likes{
    private $_tableName;
    public $conn;
    public $_p, $_g = array();

    public function __construct()
    {
        $this->_p = $_POST;
        $this->_g = $_GET;
        $this->conn = Config::GetInstance();
        if( !empty($this->_p['likes-group']) ){
            switch($this->_p['likes-group']){
                case 1:
                    $this->_tableName="comments_tournament";
                    break;
                case 2:
                    $this->_tableName="user_completed_games";
                    break;
                case 3:
                    $this->_tableName="comments_user_completed_games";
                    break;
                case 4:
                    $this->_tableName="winners";
                    break;
                default:
                    $this->tableName=false;
            }
        }
    }

    public function Init($likesGroup, $idRecord)
    {
        $data['id-ucg'] = $idRecord;
        $data['likes-group'] = $likesGroup;
        if(!empty($_SESSION['user-data']['id']))
        {
            $data['user-likes'] = $this->GetUserLikesInfo($likesGroup, $idRecord);
            $data['ucg-likes'] = $this->GetRecordLikes($likesGroup, $idRecord);
        }
        else
        {
            $data['user-likes'] = $this->GetLikesInfo($likesGroup, $idRecord);
            $data['ucg-likes'] = $this->GetRecordLikes($likesGroup, $idRecord);
        }
        return $data;
    }

    public function Like()
    {
        $idUser=(int)$_SESSION['user-data']['id'];
        $idLikesGroup = (int)$this->_p['likes-group'];
        $idRecord = (int)$this->_p['record'];
        $voted = $this->_p['voted'];
        $checkUserId = $this->conn->dbh->query("SELECT * FROM ".$this->_tableName." WHERE id =".$idRecord)->fetch(PDO::FETCH_ASSOC);
        if(!empty($checkUserId['id_user']) && (int)$checkUserId['id_user'] === $idUser)
            return false;
        if($voted === "false"){
            $sql = $this->conn->dbh->prepare("INSERT INTO likes (likes, dislikes, id_user, id_likes_group, id_record) VALUES (1, 0, :idUser, :id_likes_group, :id_record)");
        }else{
            $sql = $this->conn->dbh->prepare("UPDATE likes SET likes=1, dislikes=0 WHERE id_user=:idUser AND id_likes_group=:id_likes_group AND id_record=:id_record");
        }
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $sql->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $sql->execute();
        $stmt = $this->conn->dbh->prepare("SELECT SUM(likes)-SUM(dislikes) as likes FROM likes WHERE id_likes_group=:id_likes_group AND id_record=:id_record");
        $stmt->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $stmt->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->fetch(PDO::FETCH_ASSOC);
        return "liked"."$".$result['likes'];
    }

    public function Dislike()
    {
        $idUser=(int)$_SESSION['user-data']['id'];
        $idLikesGroup = (int)$this->_p['likes-group'];
        $idRecord = (int)$this->_p['record'];
        $voted = $this->_p['voted'];
        $checkUserId = $this->conn->dbh->query("SELECT * FROM ".$this->_tableName." WHERE id =".$idRecord)->fetch(PDO::FETCH_ASSOC);
        if(!empty($checkUserId['id_user']) && (int)$checkUserId['id_user'] === $idUser)
            return false;
        if($voted === "false"){
            $sql = $this->conn->dbh->prepare("INSERT INTO likes (likes, dislikes, id_user, id_likes_group, id_record) VALUES (0, 1, :idUser, :id_likes_group, :id_record)");
        }else{
            $sql = $this->conn->dbh->prepare("UPDATE  likes SET likes=0, dislikes=1 WHERE id_user=:idUser AND id_likes_group=:id_likes_group AND id_record=:id_record");
        }
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $sql->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $sql->execute();
        $stmt = $this->conn->dbh->prepare("SELECT SUM(likes)-SUM(dislikes) as likes FROM likes WHERE id_likes_group=:id_likes_group AND id_record=:id_record");
        $stmt->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $stmt->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->fetch(PDO:: FETCH_ASSOC);
        return "disliked"."$".$result['likes'];
    }


    public function GetLikesInfo($idLikesGroup, $idRecord)
    {
        $idLikesGroup = (int)$idLikesGroup;
        $idRecord = (int)$idRecord;
        $sql = $this->conn->dbh->prepare("SELECT likes, dislikes FROM likes WHERE id_likes_group=:id_likes_group AND id_record=:id_record");
        $sql->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $sql->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function GetUserLikesInfo($idLikesGroup, $idRecord)
    {
        $idLikesGroup = (int)$idLikesGroup;
        $idRecord = (int)$idRecord;
        $idUser=(int)$_SESSION['user-data']['id'];
        $sql = $this->conn->dbh->prepare("SELECT likes, dislikes FROM likes WHERE id_user=:idUser AND id_likes_group=:id_likes_group AND id_record=:id_record");
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $sql->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function GetRecordLikes($idLikesGroup, $idRecord)
    {
        $idLikesGroup = (int)$idLikesGroup;
        $idRecord = (int)$idRecord;
        $stmt = $this->conn->dbh->prepare("SELECT SUM(likes)-SUM(dislikes) as likes FROM likes WHERE id_likes_group=:id_likes_group AND id_record=:id_record");
        $stmt->bindParam(":id_likes_group", $idLikesGroup, PDO::PARAM_STR);
        $stmt->bindParam(":id_record", $idRecord, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO:: FETCH_ASSOC);
    }

    public function GetUserLikesCommentsUCG()
    {
        $res = $this->conn->dbh->query("SELECT cucg.id, l.likes, l.dislikes FROM likes l
	                                            LEFT JOIN comments_user_completed_games cucg ON l.id_record=cucg.id
	                                            WHERE cucg.id_section=".$this->_p['id-section']." AND l.id_user=".$_SESSION['user-data']['id']."
	                                            AND l.id_likes_group=".$this->_p['table-id'])->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($res);
    }

    public function GetCommentsLikes()
    {
        $res = $this->conn->dbh->query("SELECT  SUM(likes)-SUM(dislikes) AS rating, cucg.id FROM likes l
                                            LEFT JOIN comments_user_completed_games cucg ON l.id_record=cucg.id
                                            WHERE cucg.id_section=".$this->_p['id-section']."
                                            AND l.id_likes_group=".$this->_p['table-id']." GROUP BY l.id_record")->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($res);

    }


}