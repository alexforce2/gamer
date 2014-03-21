<?php
namespace application\modules\administration\notify;
use application\core\mvc\MainModel;
use PDO;
use classes\mailer;
use classes\sms;

class Model extends MainModel
{
    private $sms;
    public function __construct()
    {
        parent::__construct();
        $this->sms = new Sms();
    }

    public function SearchUserAjax()
    {
        $search = "%".$this->_p['search-nick']."%";
        $stmt = $this->conn->dbh->prepare("SELECT id, nick, email, phone FROM users WHERE nick LIKE :nick");
        $stmt->bindParam(":nick", $search, PDO::FETCH_ASSOC);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function SendNotify()
    {
        foreach($this->_p['users'] as $user)
        {
            //$user = array(nick, userId, email, phone);
            $user = explode("/", $user);
            $stmt = $this->conn->dbh->prepare("SELECT email, phone, profile FROM send_notify_setting WHERE notify_type=1 AND id_user=:id_user");
            $stmt->bindParam(":id_user", $user[1], PDO::PARAM_INT);
            $stmt->execute();
            $sendSetting = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user[1]!=false && (!isset($sendSetting['profile']) || $sendSetting['profile']==false)){
                $sql = $this->conn->dbh->prepare("INSERT INTO notify SET id_user = :id_user, msg = :msg, date_send=UNIX_TIMESTAMP(NOW())");
                $sql->bindParam(":id_user", $user[1], PDO::PARAM_INT);
                $sql->bindParam(":msg", $this->_p['notify'], PDO::PARAM_STR);
                $sql->execute();
            }
            if($user[2]!=false && (!isset($sendSetting['email']) || $sendSetting['email']==false))
            {
                $data = array(
                    'user' => $user[0],
                    'email' => $user[2],
                    'path' => '/skins/tpl/mail/users-notify.tpl.php',
                    'msg' => $this->_p['notify'],
                    'url' => 'http://gs11.ru/'
                );
                $msg = $this->GetTplMailMsg($data);
                $this->mail = new Mailer($msg);
                $this->Mail("Тест ГС11", $user[2], "noreply@gs11.ru", $this->mail);
            }
            if($user[3]!=false && (!isset($sendSetting['phone']) || $sendSetting['phone']==false))
            {
                $resTypePhone = $this->sms->GetTypePhone($user[3]);
                //($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($user[3], $this->_p['notify']) : $this->sms->SendSmsWorld($user[3], $this->_p['notify'], 0);
            }
        }
    }

    public function SendNotifyGroup()
    {
        $limitStart = (int)$this->_p['limit-start'];
        $limitEnd = (int)$this->_p['limit-end'];
        $notify = $this->_p['notify'];
        $sql = $this->conn->dbh->prepare("SELECT u.id, u.nick, u.email, u.phone, sns.email as email_not_send, sns.phone as phone_not_send, sns.profile as profile_not_send, sns.notify_type FROM users u
                                                LEFT JOIN send_notify_setting sns ON sns.id_user=u.id AND sns.notify_type=4
                                                ORDER BY u.id LIMIT :start, :end");
        $sql->bindParam(":start", $limitStart, PDO::PARAM_INT);
        $sql->bindParam(":end", $limitEnd, PDO::PARAM_INT);
        $sql->execute();
        $usersEmail = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql = $this->conn->dbh->prepare("INSERT INTO notify_group_msg SET msg=:msg");
        $sql->bindParam(":msg", $notify, PDO::PARAM_STR);
        $sql->execute();
        $idMsg = $this->LastInsertId();
        $value="";
        $i=0;
        foreach($usersEmail as $user){
            if($user['email']!=false && $user['email_not_send']==false){
                $data = array(
                    'user' => $user['nick'],
                    'email' => $user['email'],
                    'path' => '/skins/tpl/mail/users-notify.tpl.php',
                    'msg' => $notify,
                    'url' => 'http://gs11.ru/'
                );
                $msg = $this->GetTplMailMsg($data);
                $this->mail = new Mailer($msg);
                $this->Mail("Тест ГС11", $user['email'], "noreply@gs11.ru", $this->mail);
            }
            if($user['phone']!=false && $user['phone_not_send']==false){
                $resTypePhone = $this->sms->GetTypePhone($user['phone']);
                //($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($user['phone'], $notify) : $this->sms->SendSmsWorld($user['phone'], $notify, 0);
            }
            if($user['id']!=false && $user['profile_not_send']==false){
                $value.=($i===0) ?"($user[id],$idMsg,UNIX_TIMESTAMP(NOW()))" :",($user[id],$idMsg,UNIX_TIMESTAMP(NOW()))" ;
            }
            $i++;
        }
        $sql = $this->conn->dbh->prepare("INSERT INTO notify_group(id_user,id_msg,date_send) VALUES ".$value);
        $sql->execute();
        return true;

    }

    public function GetNotifyHistory()
    {
        $stmt = $this->conn->dbh->prepare("SELECT u.nick, n.date_send, n.msg FROM notify n LEFT JOIN users u ON u.id = n.id_user");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function GetAllUsersCount()
    {
        $stmt = $this->conn->dbh->prepare("SELECT COUNT(id) as users_count FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
