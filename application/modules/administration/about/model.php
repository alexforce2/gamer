<?php
namespace application\modules\administration\about;
use application\core\mvc\MainModel;
use classes\render;
use PDO;
use classes\SimpleImage;

class Model extends MainModel
{
    function __construct()
    {
        parent::__construct();
    }

    public function CountMessage()
    {
        $stmt = $this->conn->dbh->prepare("SELECT  COUNT(`index`) FROM message_contact WHERE `index` = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
    public function UpdateContact($id)
    {
        $query = $this->conn->dbh->prepare("UPDATE `message_contact` SET `index`= 1 WHERE `id` = $id");
        $query->execute();
    }
    public function GetMessageContact()
    {
        $stmt = $this->conn->dbh->prepare("SELECT m.id_user, m.name_user, m.email, m.text, r.name_rubric, u.nick,m.id
        FROM message_contact m  LEFT JOIN rubric_contact r ON m.id_rubric = r.id
        LEFT JOIN users u ON m.id_user = u.id
        WHERE `index` = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
           /*********Начало работа с играми***********/

    public function ListGameForever()
    {
        return $this->conn->dbh->query("SELECT id, name_game FROM games_forever")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetDataGameForever()
    {
        return $this->FetchObj("SELECT *  FROM games_forever WHERE id = :id", array(":id" => $_GET["id"]));
    }
    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    public function WorkImg($params,$path,$bool)
    {
        if($bool == false)
        {
            unlink($this->rootDir .$path.$params);
        }else
        {
            $img =  basename($params);
            copy($this->rootDir.$params,$this->rootDir.$path.$img);
            unlink($this->rootDir . $params);
        }
    }

    public function SetDataGameForever($params)
    {

        $this->WorkImg($params[1]['value'],"storage/legend-game/",true);
        $imgLink = array_splice($params,0,2);
        $stmt = $this->conn->dbh->prepare("INSERT INTO games_forever SET name_game = :name_game,
        description_game = :description, link_game_anchor =:link_anchor, link_game = :link_game, source_img = :source_img");
        $arr = array(':name_game',':description',':link_anchor',':link_game');
        foreach($params as $key => $value)
        {
            $stmt->bindParam("$arr[$key]",$value['value'],PDO::PARAM_STR);
        }
        $stmt->bindParam(":source_img", basename($imgLink[1]['value']), PDO::PARAM_STR);
        $stmt->execute();
    }
    public function EditDataGameForever($params,$id)
    {

        $this->WorkImg($params[1]['value'],"storage/legend-game/",true);
        $imgLink = array_splice($params,0,2);
        $stmt = $this->conn->dbh->prepare("UPDATE games_forever SET
        description_game = :description, link_game_anchor =:link_anchor, link_game = :link_game,
        source_img = :source_img WHERE id = :id");
        $arr = array(':description',':link_anchor',':link_game');
        foreach($params as $key => $value)
        {
            $stmt->bindParam("$arr[$key]",$value['value'],PDO::PARAM_STR);
        }
        $stmt->bindParam(":source_img", basename($imgLink[1]['value']), PDO::PARAM_STR);
        $stmt->bindParam(":id", $imgLink[0]['value'], PDO::PARAM_STR);
        $stmt->execute();
        if($imgLink[1]['name']== 'deletedImg[]')
        {
            $this->WorkImg($imgLink[1]['value'],"storage/legend-game/",false);
            $stmt = $this->conn->dbh->prepare("UPDATE games_forever SET source_img = '' WHERE id = $id");
            $stmt->execute();
        }
    }

    public function RemoveGameForever($id)
    {
        $img = $this->conn->dbh->query("SELECT source_img FROM games_forever WHERE id = $id")->fetchAll(PDO::FETCH_ASSOC);;
        $this->WorkImg($img[0]['source_img'],"storage/legend-game/",false);
        $stmt = $this->conn->dbh->prepare("DELETE FROM games_forever WHERE id = :id");
        $stmt->bindParam(":id",$id, PDO::PARAM_INT);
        $stmt->execute();
    }


             /*********Начало работа с благодарностями********/

    public function ListThanks()
    {
        return $this->conn->dbh->query("SELECT id, name_partner FROM thanks")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetDataThanks()
    {
        return $this->FetchObj("SELECT *  FROM thanks WHERE id = :id", array(":id" => $_GET["id"]));
    }
    public function SetDataThanks($params)
    {
        $this->WorkImg($params[1]['value'],"storage/thanks/",true);
        $imgLink = array_splice($params,0,2);
        $stmt = $this->conn->dbh->prepare("INSERT INTO thanks SET name_partner = :name_partner,
         link_anchor =:link_anchor, link = :link, text = :text,source_img = :source_img");
        $arr = array(':name_partner',':link_anchor',':link',':text');
        foreach($params as $key => $value)
        {
            $stmt->bindParam("$arr[$key]",$value['value'],PDO::PARAM_STR);
        }
        $stmt->bindParam(":source_img", basename($imgLink[1]['value']), PDO::PARAM_STR);
        $stmt->execute();
    }
    public function RemoveDataThanks($id)
    {
        $img = $this->conn->dbh->query("SELECT source_img FROM thanks WHERE id = $id")->fetchAll(PDO::FETCH_ASSOC);;
        $this->WorkImg($img[0]['source_img'],"storage/thanks/",false);
        $stmt = $this->conn->dbh->prepare("DELETE FROM thanks WHERE id = :id");
        $stmt->bindParam(":id",$id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function EditDataThanks($params,$id)
    {
        $this->WorkImg($params[1]['value'],"storage/thanks/",true);
        $imgLink = array_splice($params,0,2);
        $stmt = $this->conn->dbh->prepare("UPDATE thanks SET name_partner = :name_partner,
        link_anchor =:link_anchor, link = :link, text = :text, source_img = :source_img WHERE id = :id");
        $arr = array(':name_partner',':link_anchor',':link',':text');
        foreach($params as $key => $value)
        {
            $stmt->bindParam("$arr[$key]",$value['value'],PDO::PARAM_STR);
        }
        $stmt->bindParam(":source_img", basename($imgLink[1]['value']), PDO::PARAM_STR);
        $stmt->bindParam(":id", $imgLink[0]['value'], PDO::PARAM_STR);
        $stmt->execute();
        if($imgLink[1]['name']== 'deletedImg[]')
        {
            $this->WorkImg($imgLink[1]['value'],"storage/thanks/",false);
            $stmt = $this->conn->dbh->prepare("UPDATE thanks SET source_img = '' WHERE id = $id");
            $stmt->execute();
        }
    }
}