<?php
namespace application\modules\tournament;
use application\core\mvc\MainModel;
use classes\sms;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        $this->sms = new Sms();
        parent::__construct();
    }

    /* Комментарии начало */
    public function AddComment()
    {
        // todo сделать проверку на существование элемента и не соответствии текущему user
        // todo сделать проверку на id турнира
        $stmt = $this->conn->dbh->prepare("INSERT INTO comments_tournament (`date`,`id_user`,`id_user_answer`, `comment`, `id_section`) VALUES(UNIX_TIMESTAMP(NOW()), :id_user, :id_user_answer, :comment, :id_section)");
        $stmt->bindParam(":id_user", $_SESSION['user-data']['id'], PDO::PARAM_INT);
        $stmt->bindParam(":id_user_answer", $_POST['id-user-answer'], PDO::PARAM_INT);
        $stmt->bindParam(":comment", $_POST['comment'], PDO::PARAM_STR);
        $stmt->bindParam(":id_section", $_SESSION['id_tournament'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $this->conn->dbh->lastInsertId();
        return json_encode($this->LastComment($id));
    }

    public function ListComments()
    {
        $result = $this->conn->dbh->query("SELECT  c.*, u.nick, u.img_avatar, u2.nick as nick_answer, u2.img_avatar as img_avatar_answer  FROM comments_tournament c
                                            LEFT JOIN users u ON c.id_user = u.id
                                            LEFT JOIN users u2 ON c.id_user_answer = u2.id WHERE id_section = ". $_SESSION['id_tournament']." LIMIT 0, 1000")->fetchAll(PDO::FETCH_OBJ);
        return json_encode($result);
    }

    public function RemoveComment()
    {
        $this->conn->dbh->query("DELETE FROM comments_tournament WHERE id = ".(int)$_POST['id']. " AND id_user = ". $_SESSION['user-data']['id'] . " AND id_section = ".$_SESSION['id_tournament']);
    }

    public function MarkSpam()
    {
        $stmt = $this->conn->dbh->exec("INSERT INTO spam_materials (date, id_user, id_comment, id_section, comment_product) VALUES (UNIX_TIMESTAMP(NOW()), :id_user, :id_comment, :id_section, :comment_product)");
        $stmt->bindParam(":id_user", $_POST['id-user'], PDO::PARAM_INT);
        $stmt->bindParam(":id_comment", $_POST['id-comment'], PDO::PARAM_INT);
        $stmt->bindParam(":id_section", $_POST['id-section'], PDO::PARAM_INT);
        $stmt->bindParam(":comment_product", $_POST['comment-product'], PDO::PARAM_INT);
        $stmt->execute();
    }

    private function LastComment($id)
    {
        return $this->conn->dbh->query("SELECT c.*, u.nick, u.img_avatar, u2.nick as nick_answer, u2.img_avatar as img_avatar_answer  FROM comments_tournament c
                                            LEFT JOIN users u ON c.id_user = u.id
                                            LEFT JOIN users u2 ON c.id_user_answer = u2.id WHERE c.id = ". (int)$id. " AND id_section =".$_SESSION['id_tournament'])->fetchAll(PDO::FETCH_OBJ);
    }

    /* Комментарии Конец */

    public function GetTournament()
    {
        return $this->conn->dbh->query("SELECT t.*, g.name as game, g.source_img_s as game_img  FROM tournaments t LEFT JOIN games g ON g.id=t.id_game WHERE t.id =".(int)$this->_g['id'])->fetch(PDO::FETCH_OBJ);
    }


    public function GetMembers()
    {
        return $this->conn->dbh->query("SELECT t.id_tournament, t.stage, u.*  FROM member_tournament t
                                    LEFT JOIN users u ON u.id=t.id_user WHERE t.id_tournament = ".(int)$this->_g['id'])->fetchAll(PDO::FETCH_OBJ);

    }

    public function GetWinner($stage)
    {
        return $this->conn->dbh->query("SELECT * FROM info_winner WHERE id_tournament = ".(int)$this->_g['id']."
        AND stage = ".(int)$stage. " AND id_user = " . (int)$_SESSION['user-data']['id'])->rowCount();
    }

    public function ConfirmParticipation()
    {
        $stmt = $this->conn->dbh->query("SELECT t.id_tournament, t.stage, t.game_over, t.id_opponent, u.*, u2.nick as nick_opponent, u2.img_avatar as img_avatar_opponent,
                                        u2.about_me as about_me_opponent, u2.game_experience as game_experience_opponent
                                        FROM member_tournament t
                                        LEFT JOIN users u2 ON u2.id=t.id_opponent
                                        LEFT JOIN users u ON u.id=t.id_user WHERE t.id_user = ".(int)$_SESSION['user-data']['id'] . " AND t.id_tournament = ".(int)$this->_g['id']." LIMIT 1");
        return $stmt->rowCount() == 1 ? $stmt->fetch(PDO::FETCH_OBJ) : false;
    }
    public function TableMember()
    {
        $stmt = $this->conn->dbh->query("SELECT t.id_tournament, t.stage, t.game_over, t.id_opponent, u.*
                                        FROM member_tournament t LEFT JOIN users u ON u.id=t.id_user");
        return $stmt->rowCount() > 1 ? $stmt->fetch(PDO::FETCH_OBJ) : false;
    }
    public function ConfirmMemberTournament()
    {
        if($_SESSION['user-data']['group'] >= 1)
        {
            $description = "Участие в турнире по Dota 2, анулирование тарифа 'группа C'";
            $group = ($_SESSION['user-data']['group'] == 1) ? "0" : $_SESSION['user-data']['group'];
            $sum = "0";
            $stmt = $this->conn->dbh->prepare("INSERT INTO transactions_payments SET `id_user` = :id, date = UNIX_TIMESTAMP(NOW()), description = :description, `sum` = :sum, `group` = :group");
            $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            $stmt->bindParam(":sum", $sum, PDO::PARAM_STR);
            $stmt->bindParam(":group", $group, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $this->conn->dbh->prepare("UPDATE users SET `group` = :group WHERE id = :id");
            $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt->bindParam(":group", $group, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $this->conn->dbh->prepare("INSERT INTO member_tournament SET id_user = :id, id_tournament = :id_tournament, date = UNIX_TIMESTAMP(NOW())");
            $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_tournament", $this->_g['id'], PDO::PARAM_INT);
            $stmt->execute();
            $this->_getRefreshDataUser();
        }
        else
        {
            return "Внутренняя ошибка";
        }
    }

    public function TableMembers()
    {
        return $this->conn->dbh->query("SELECT t.stage, t.game_over, t.id_user, t.id_opponent, u.nick as first_opponent, u2.nick as second_opponent, u2.id as id_opponent
                                                FROM member_tournament t
                                                LEFT JOIN users u ON u.id=t.id_user
                                                LEFT JOIN users u2 ON u2.id=t.id_opponent WHERE t.id_tournament = ".(int)$this->_g['id']." GROUP BY t.id_members")->fetchAll(PDO::FETCH_OBJ);

    }
    public function SettingsTournament($stage)
    {
        return $this->conn->dbh->query("SELECT t.*, h.* FROM settings_tournament t
                                        LEFT JOIN heroes_games h ON h.id = t.id_hero WHERE t.id_tournament = ".(int)$this->_g['id']." AND t.stage = " . (int)$stage)->fetch(PDO::FETCH_OBJ);
    }
    public function SetWinner()
    {
        if ($this->_p['text-winner'] == "" || strlen($this->_p['text-winner']) < 7)
            return "Добавьте описание";
        else if ($this->_p['img-winner'] == "" )
            return 'Прикрепите изображение';
        else
        {
            $arr = $this->ConfirmParticipation();

            $img = json_decode($this->_p['img-winner']);
            $stmt = $this->conn->dbh->prepare("INSERT INTO info_winner SET `id_user` = :id, date = UNIX_TIMESTAMP(NOW()), text = :text, `img` = :img, id_tournament = :id_tournament, stage = :stage");
            $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
            $stmt->bindParam(":id_tournament", $this->_g['id'], PDO::PARAM_INT);
            $stmt->bindParam(":text", $this->_p['text-winner'], PDO::PARAM_STR);
            $stmt->bindParam(":stage", $arr->stage, PDO::PARAM_STR);
            $stmt->bindParam(":img", $img->filename_b, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function SearchOpponent($stage)
    {
        $resOpponent = $this->conn->dbh->query("SELECT * FROM member_tournament
        WHERE game_over = 0 AND id_opponent = 0 AND stage = ".(int)$stage . " AND id_user <> ".(int)$_SESSION['user-data']['id'] . " AND id_tournament = ".(int)$this->_g['id']."
        ORDER BY RAND() LIMIT 1")->fetch(PDO::FETCH_OBJ);
        if(!empty($resOpponent->id_user))
        {
            $idMembers = $resOpponent->id_user . rand(10,99);
            $this->conn->dbh->exec("UPDATE member_tournament SET id_opponent = ".$resOpponent->id_user.", id_members = ".$idMembers.", id_tournament = ".(int)$this->_g['id']."
            WHERE id_user = ".(int)$_SESSION['user-data']['id']);
            $this->conn->dbh->exec("UPDATE member_tournament SET id_opponent = ".(int)$_SESSION['user-data']['id'].", id_members = ".$idMembers.", id_tournament = ".(int)$this->_g['id']."
             WHERE id_user = ".$resOpponent->id_user);
            return $this->conn->dbh->query("SELECT t.id_tournament, t.stage, t.game_over, t.id_opponent, u.*, u2.id as id_opponent, u2.nick as nick_opponent, u2.img_avatar as img_avatar_opponent,
                                            u2.about_me as about_me_opponent, u2.game_experience as game_experience_opponent
                                            FROM member_tournament t
                                            LEFT JOIN users u2 ON u2.id=t.id_opponent
                                            LEFT JOIN users u ON u.id=t.id_user
                                            WHERE t.id_user = ".(int)$_SESSION['user-data']['id'] . " AND t.id_tournament = ".(int)$this->_g['id']." LIMIT 1")->fetch(PDO::FETCH_OBJ);

        }
    }

    public function AddMessageChat()
    {
        $stmt = $this->conn->dbh->prepare("INSERT INTO chat (id_user, id_tournament, msg, date, stage) VALUES (:id, :id_tournament, :msg, UNIX_TIMESTAMP(NOW()), :stage)");
        $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
        $stmt->bindParam(":stage", $_SESSION['user']['stage'], PDO::PARAM_INT);
        $stmt->bindParam(":msg", $_POST['message_text'], PDO::PARAM_INT);
        $stmt->bindParam(":id_tournament", $this->_g['id'], PDO::PARAM_INT);
        $stmt->execute();
    }


    public function GetChatMsg($from_last_act = false)
    {
        $opponent = $this->OpponentTournament();
        $opponent->id = ($opponent->id) ? $opponent->id : 0;
        $data = "";
        $result = ($from_last_act != false) ? $this->conn->dbh->query("SELECT * FROM chat WHERE id_tournament = ".$_SESSION['id_tournament']." AND id > ".(int)$from_last_act."
        AND (id_user = ".(int)$_SESSION['user-data']['id']." OR id_user = ".$opponent->id.") AND stage = ".(int)$_SESSION['user']['stage']." ORDER BY date ASC, id ASC") :
            $this->conn->dbh->query("SELECT * FROM chat WHERE (id_user = ".(int)$_SESSION['user-data']['id']." OR id_user = ".$opponent->id.")
            AND id_tournament = ".$_SESSION['id_tournament']." AND stage = ".(int)$_SESSION['user']['stage']."
            ORDER BY date ASC, id ASC");

        if ($result->rowCount() > 0)
        {
            $arr = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($arr as $r)
            {
                $date = ($r['date'] > time()-3600*24) ? date("H:i:s",$r['date']) : date("d.m.Y",$r['date']);
                $data['log'] .= ($r['id_user'] == $_SESSION['user-data']['id']) ?
                    '<p class="chat_post_my">
                        <img class="avatar-chat" src="'.$_SESSION['user-data']['img_avatar'].'"/>
                        <div class="mess_text_area">
                            <span class="chat_mess_time">'.$date.'</span>
                        <img src="/skins/img/interface/chat-msg-li.png" />
                        '.$r['msg'].'</div>
                    </p><div class="clear"></div>' :
                    '<p class="chat_post_my">
                        <div class="mess_text_area_opponent">
                            <img src="/skins/img/interface/chat-msg-li-opponent.png" />
                            <span class="chat_mess_time">'.$date.'</span>
                            '.$r['msg'].'
                        </div>
                        <img class="avatar-chat-opponent" src="'.$opponent->img_avatar.'"/>
                    </p><div class="clear"></div>';
                $data['last_act'] = $r['id'];
            }
        }
        else
        {
            $data['log'] = "";
            $data['last_act'] = 0;
        }
        return $data;
    }

    public function ListTournaments()
    {
        return $this->conn->dbh->query("SELECT t.*, g.name as game, g.source_img_s as game_img  FROM tournaments t
                                        LEFT JOIN games g ON g.id=t.id_game WHERE t.state = '2' OR t.state = '3'")->fetchAll(PDO::FETCH_OBJ);

    }
    public function ListWinners()
    {

        return $this->conn->dbh->query("SELECT t.header, t.end_date, t.pay, w.id, w.description, u.nick, g.name as game, g.source_img_s as game_img  FROM winners w
                                        LEFT JOIN users u ON u.id = w.winner
                                        LEFT JOIN tournaments t ON w.id_tournament = t.id
                                        LEFT JOIN games g ON g.id=t.id_game ORDER BY end_date ASC")->fetchAll(PDO::FETCH_OBJ);

    }
    public function NumberViews($id)
    {
        $stmt =  $this->conn->dbh->prepare("UPDATE winners SET count_views = count_views+1 WHERE id = ". (int)$id);
        $stmt->execute();
    }
    public function WinnerPage($id)
    {
        return $this->conn->dbh->query("SELECT  t.header, t.end_date, t.pay, w.*,w.id as id_ucg, u.nick, g.name as game, g.source_img_s as game_img  FROM winners w
                                        LEFT JOIN users u ON u.id = w.winner
                                        LEFT JOIN tournaments t ON w.id_tournament = t.id
                                        LEFT JOIN games g ON g.id=t.id_game WHERE w.id = ". (int)$id)->fetch(PDO::FETCH_OBJ);
    }

    public function SendNoticeOpponent()
    {
        $phone = $this->conn->dbh->query("SELECT phone FROM users WHERE id = ". (int)$this->_p['opponent'])->fetch(PDO::FETCH_OBJ);
        $resTypePhone = $this->sms->GetTypePhone($phone->phone);
        $_SESSION['sms-phone'] = $resTypePhone['phone'];
        $msg = "Пользователь ожидает вас на турнире по игре DOTA 2";
        ($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($_SESSION['sms-phone'], $msg) : $this->sms->SendSmsWorld($_SESSION['sms-phone'], $msg, 0);
    }
    public function SearchNoticeOpponent()
    {
        $phone = $this->conn->dbh->query("SELECT phone FROM users WHERE phone IS NOT NULL AND id <> ". $_SESSION['user-data']['id']. " ORDER BY rand() LIMIT 1")->fetch(PDO::FETCH_OBJ);
        $resTypePhone = $this->sms->GetTypePhone($phone->phone);
        $_SESSION['sms-phone'] = $resTypePhone['phone'];
        $msg = "Один из участников приглашает вас принять участие в турнире по игре DOTA 2";
        ($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($_SESSION['sms-phone'], $msg) : $this->sms->SendSmsWorld($_SESSION['sms-phone'], $msg, 0);
    }


    private function OpponentTournament()
    {
        return $this->conn->dbh->query("SELECT u.* FROM member_tournament mt
                                        LEFT JOIN users u ON u.id = mt.id_opponent WHERE mt.id_tournament = ".$_SESSION['id_tournament']." AND mt.id_user =".(int)$_SESSION['user-data']['id'])->fetch(PDO::FETCH_OBJ);
    }
}