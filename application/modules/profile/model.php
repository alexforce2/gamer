<?php
namespace application\modules\profile;
use application\core\mvc\MainModel;
use PDO;
use classes\OftenFunctions;

class Model extends MainModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function MainEditUserData()
    {
        if ($this->_p['first-name'] == "" || strlen($this->_p['first-name']) < 2)
            return "Заполните корректно поле Имя";
        else if ($this->_p['last-name'] == "" || strlen($this->_p['last-name']) < 2)
            return "Заполните корректно поле Фамилия";
        else if ($this->_p['patronymic'] == "" || strlen($this->_p['patronymic']) < 2)
            return "Заполните корректно поле Отчество";
        else if ($this->_p['sex'] == "")
            return "Укажите свой пол";
        else
        {
            $birthday = strtotime($this->_p['birthday']);
            $stmt = $this->conn->dbh->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name,
                                        patronymic = :patronymic, birthday = :birthday, sex = :sex, about_me = :about_me  WHERE id = :id");
            $stmt->bindParam(":first_name", $this->_p['first-name'], PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $this->_p['last-name'], PDO::PARAM_STR);
            $stmt->bindParam(":patronymic", $this->_p['patronymic'], PDO::PARAM_STR);
            $stmt->bindParam(":birthday", $birthday, PDO::PARAM_INT);
            $stmt->bindParam(":sex", $this->_p['sex'], PDO::PARAM_INT);
            $stmt->bindParam(":about_me", $this->_p['about-me'], PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt->execute();
            $this->GetRefreshDataUser();
        }
    }
    public function MainEditGamerData()
    {
        $stmt = $this->conn->dbh->prepare("UPDATE users SET game_experience = :game_experience, love_genre = :love_genre,
                                    love_complexity = :love_complexity, love_game = :love_game  WHERE id = :id");
        $stmt->bindParam(":game_experience", $this->_p['game-experience'], PDO::PARAM_STR);
        $stmt->bindParam(":love_genre", $this->_p['love-genre'], PDO::PARAM_STR);
        $stmt->bindParam(":love_complexity", $this->_p['love-complexity'], PDO::PARAM_STR);
        $stmt->bindParam(":love_game", $this->_p['love-game'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
        $stmt->execute();
        $this->GetRefreshDataUser();
    }

    /*
    *   ОБЩИЕ ДАННЫЕ
    */
    public function MainEditUserOtherData()
    {
        $result = [];
        $city       = $this -> _p['city'];
        $check_city = $this -> conn -> dbh -> query("SELECT name FROM city WHERE name = '". $city ."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        if ( !empty($check_city) ) {
            $stmt = $this -> conn -> dbh -> prepare("UPDATE users SET nick = :nick, skype = :skype, steam = :steam, icq = :icq, about_me = :about_me, city = :city  WHERE id = :id");
            $stmt -> bindParam(":nick",       $this -> _p['nick'], PDO::PARAM_STR);
            $stmt -> bindParam(":skype",       $this -> _p['skype'], PDO::PARAM_STR);
            $stmt -> bindParam(":steam",       $this -> _p['steam'], PDO::PARAM_STR);
            $stmt -> bindParam(":icq",       $this -> _p['icq'], PDO::PARAM_INT);
            $stmt -> bindParam(":about_me",   $this -> _p['about-me'], PDO::PARAM_STR);
            $stmt -> bindParam(":city",       $this -> _p['city'], PDO::PARAM_STR);
            $stmt -> bindParam(":id",         $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt -> execute();
            $result['city_success'] = true;
            $this -> GetRefreshDataUser();
        } else {
            $result['city_success'] = false;
        }

        return json_encode($result);

    }
    public function GetGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetGenre()
    {
        return $this->conn->dbh->query("SELECT * FROM genre")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetRanks()
    {
        return $this->conn->dbh->query("SELECT * FROM user_rank WHERE `numeric` <= '".$_SESSION['user-data']['complete_games']."' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }
    public function GetAwards()
    {
        return $this->conn->dbh->query("SELECT * FROM user_award WHERE id_user <= '".$_SESSION['user-data']['id']."' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }

    public function DeleteAvatar()
    {
        $this->conn->dbh->exec("UPDATE users SET img_avatar = '', img_avatar_b = '' WHERE id = ".(int)$_SESSION['user-data']['id']);
        $this->GetRefreshDataUser();
    }

    public function SetAvatar($fileSmall, $fileBig)
    {
        $stmt = $this->conn->dbh->prepare("UPDATE users SET img_avatar = ?, img_avatar_b = ? WHERE id = ".(int)$_SESSION['user-data']['id']);
        $stmt->execute(array($fileSmall, $fileBig));
        $this->GetRefreshDataUser();
    }
    public function SetDir($dir)
    {
        $stmt = $this->conn->dbh->prepare("UPDATE users SET path = ? WHERE id = ?");
        $stmt->execute(array($dir, $_SESSION['user-data']['id']));
        $this->GetRefreshDataUser();
    }

    /*
    *   ПОЛУЧАЕТ НАЗВАНИЕ ГОРОДОВ
    */
    public function GetCities() {
        $result = [];

        $query =  $this -> _p['query'];
        $limit = !empty($this -> _p['limit']) ? $this -> _p['limit'] : '10';

        if ( !empty($query) ) {
            $query = OftenFunctions::getCorrectText($query);
            $query = ' AND city.name like "%'. $query.'%"';

            $sql   = "SELECT DISTINCT city.name FROM city WHERE city.socr in ('г', 'п', 'аул') ". $query ." LIMIT ". $limit;
            $sql   = $this -> conn -> dbh -> query($sql);
            foreach ( $sql as $value ) {
                $result['suggestions'][] = $value[0];
            }
        }

        return json_encode($result);
    }

    public function GetAllNotify()
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT * FROM notify WHERE id_user=:id_user");
        $stmt->bindParam(":id_user", $idUser, PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function GetNotify($id)
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT * FROM notify WHERE id_user=:id_user AND id=:id");
        $stmt->bindParam(":id_user", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function ReadNotify($id)
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("UPDATE notify SET date_read=UNIX_TIMESTAMP(NOW()) WHERE id_user=:id_user AND id=:id");
        $stmt->bindParam(":id_user", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->GetRefreshDataUser();
    }

    public function GetUnreadNotify()
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT COUNT(*) as `count` FROM notify WHERE id_user=:id_user AND date_read IS NULL");
        $stmt->bindParam(":id_user", $idUser, PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function GetNotifySetting()
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        return $this->conn->dbh->query("SELECT * FROM send_notify_setting WHERE id_user=".$idUser)->fetchAll(PDO::FETCH_OBJ);
    }

    public function SetUserNotifySetting()
    {
        $recordsCheck = $this->GetNotifySetting();
        $notifyType = $this->GetNotifyType();
        $i=0;
        foreach($notifyType as $notify){
            $query = (isset($recordsCheck[$i]->notify_type))?(boolean)$recordsCheck[$i]->notify_type:false;
            $email= (isset($this->_p[$notify->name.'-email'])) ? (int)$this->_p[$notify->name.'-email']: 0;
            $phone= (isset($this->_p[$notify->name.'-phone'])) ? (int)$this->_p[$notify->name.'-phone']: 0;
            $profile= (isset($this->_p[$notify->name.'-profile'])) ?(int)$this->_p[$notify->name.'-profile'] : 0;
            $this->SetSettingRecord($email, $phone, $profile, $query, $notify->id);
            $i++;
        }
    }

    public function SetSettingRecord($email, $phone, $profile, $query, $notifyType)
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $query = ($query) ? "UPDATE send_notify_setting SET  email=:email, phone=:phone, profile=:profile WHERE id_user=:id_user AND notify_type=:notifyType" : "INSERT INTO send_notify_setting SET id_user=:id_user, notify_type=:notifyType, email=:email, phone=:phone, profile=:profile";
        $stmt = $this->conn->dbh->prepare($query);
        $stmt->bindParam(":id_user", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":email", $email, PDO::PARAM_INT);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_INT);
        $stmt->bindParam(":notifyType", $notifyType, PDO::PARAM_INT);
        $stmt->bindParam(":profile", $profile, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function GetNotifyType()
    {
        $stmt = $this->conn->dbh->prepare("SELECT * FROM notify_type");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}

