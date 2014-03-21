<?php
namespace application\modules\main;
use application\core\mvc\MainModel;
use classes\HashPass;
use classes\mailer;
use classes\sms;
use PDO;

class Model extends MainModel
{
    private $hashPass, $email, $mail, $idUser, $sms;

    public function __construct()
    {
        $this->sms = new Sms();
        $this->hashPass = new HashPass(15);
        parent::__construct();
        $this->email = (isset($this->_p['email'])) ? $this->_p['email'] : "";

        /* Рассылка для пользователей */
        if(isset($_GET['notice-users']) && $_GET['notice-users'] == 'email')
            $this->SendEmailNotice();
        if(isset($_GET['notice-users']) && $_GET['notice-users'] == 'phone')
            $this->SendPhoneNotice();
    }
    public function GetAuth()
    {
        $resTypePhone = $this->sms->GetTypePhone($this->_p['login']);
        $phone = substr($this->_p['login'], -10);
        if(ctype_digit($resTypePhone['phone']) > 0)
        {
            if(!preg_match("/^\+?([87](?!95[4-79]|99[^2457]|907|94[^0]|336|986)([348]\d|9[0-689]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55119\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[457]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[34569]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9})$/", $this->_p['login']))
                return "Вы ошиблись при вводе данных";
        }
        $hash = md5(rand() . date("d.m.Y H:i"));
        $stmt = $this->conn->dbh->prepare("SELECT * FROM users WHERE email = :login OR phone = :login OR phone = :phone");
        $stmt->bindParam(":login", $this->_p['login'], PDO::PARAM_STR);
        $stmt->bindParam(":phone", $resTypePhone['phone'], PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() == 1)
        {
            $arrData = $stmt->fetch(PDO::FETCH_ASSOC);
            $passGood = $this->hashPass->Verify($this->_p['pass'], $arrData['pass']);
            if($passGood)
            {
                $query = $this->conn->dbh->prepare("UPDATE users SET temp_key = ?, date_last_visit = UNIX_TIMESTAMP(NOW()), num_try_error_auth = 0, ip = ? WHERE id = " . $arrData['id']);
                $query->execute(array($hash, $this->ipUser));
                $arrData = $this->conn->dbh->query("SELECT * FROM users WHERE id = " . $arrData['id'])->fetch(PDO::FETCH_ASSOC);
                if(isset($this->_p['check']))
                    $this->RememberMe($hash, $arrData['id'], $arrData['date_last_visit']);
                $this->RedirectAuth($arrData);
            }
            else
            {
                $query = $this->conn->dbh->prepare("UPDATE users SET date_error_auth = UNIX_TIMESTAMP(NOW()), num_try_error_auth = num_try_error_auth + 1, ip = :ip
                                                    WHERE email = :login OR phone = :login");
                $query->bindParam(':login', $this->_p['login'], PDO::PARAM_STR);
                $query->bindParam(':ip', $this->ipUser, PDO::PARAM_STR);
                $query->execute();
                return "Вы ошиблись при вводе данных";
            }
        }
        return "Вы ошиблись при вводе данных";
    }

    public function RegistrationUser()
    {
        if (!$_SESSION['sms-phone'])
            return "Заполните поле Телефон";
        else if (!preg_match("/^\+?([87](?!95[4-79]|99[^2457]|907|94[^0]|336|986)([348]\d|9[0-689]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55119\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[457]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[34569]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9})$/", $_SESSION['sms-phone']))
            return "Телефон не корректен";
        else if (strlen($this->_p['pass']) < 7)
            return "Пароль должен быть не менее 8 символов";
        else if ($this->_p['pass'] !== $this->_p['pass-conf'])
            return "Пароли не совподают";
        else if ($_SESSION['sms-code'] != $this->_p['code-reg'])
            return 'Код указан неверно';
        else if ($this->ExistPhone())
            return "Телефон уже занят";
        else
        {
            $query = $this->conn->dbh->prepare("INSERT INTO users SET phone = ?, pass = ?, date_registration = UNIX_TIMESTAMP(NOW()), ip = ?");
            $query->execute(array($_SESSION['sms-phone'], $this->hashPass->Hash($this->_p['pass']), $this->ipUser));
            $stmt = $this->conn->dbh->prepare("SELECT * FROM users WHERE phone = ?");
            $stmt->execute(array($_SESSION['sms-phone']));
            $this->RedirectAuth($stmt->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function SendSmsRegistration()
    {
        $_SESSION['sms-code'] = rand(10000, 99999);
        $resTypePhone = $this->sms->GetTypePhone($this->_p['phone']);
        $_SESSION['sms-phone'] = $resTypePhone['phone'];

        if($this->ExistPhone())
            return "Телефон уже занят#1";
        $msg = $_SESSION['sms-code'] . " - этот код позволяет вам зарегистрироваться на нашем сайте";
        ($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($_SESSION['sms-phone'], $msg) : $this->sms->SendSmsWorld($_SESSION['sms-phone'], $msg, 0);
    }
    public function RestoreExistUser()
    {
        if(isset($this->_p['phone']))
        {
            if(!preg_match("/^\+?([87](?!95[4-79]|99[^2457]|907|94[^0]|336|986)([348]\d|9[0-689]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55119\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[457]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[34569]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9})$/", $this->_p['phone']))
                return "Телефон не найден#1";
            $_SESSION['sms-phone'] = $this->sms->GetTypePhone($this->_p['phone'])['phone'];
            if(!$this->ExistPhone())
                return "Телефон не найден#1";
        }
        else
        {
            if(!$this->ExistEmail() || empty($this->_p['email']))
                return "Email не найден#2";
        }

    }
    public function RestoreSendPhone()
    {
        if($this->_p['code-captcha-input'] == $_SESSION['code-captcha'])
        {
            $_SESSION['sms-code'] = rand(10000, 99999);
            $_SESSION['hash-success'] = md5(rand(10000, 99999) . mktime());
            $this->SetTableRestoreUsers("phone", $_SESSION['hash-success']);
            $msg = $_SESSION['sms-code'] . " - этот код позволяет вам восстановить аккаунт на нашем сайте";
            $resTypePhone = $this->sms->GetTypePhone($_SESSION['sms-phone']);
            ($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($_SESSION['sms-phone'], $msg) : $this->sms->SendSmsWorld($_SESSION['sms-phone'], $msg, 0);
            return true;
        }
        return false;
    }
    public function RestoreSendEmail()
    {
        if($this->_p['code-captcha-input'] == $_SESSION['code-captcha'])
        {
            $_SESSION['hash-success'] = md5(rand(10000, 99999) . mktime());
            $this->SetTableRestoreUsers("email", $_SESSION['hash-success']);
            $query = $this->conn->dbh->prepare("SELECT r.date as date_restore, r.hash, u.* FROM users_restore r
            LEFT JOIN users u ON r.id_user = u.id  WHERE id_user = ? ORDER BY r.date DESC LIMIT 1");
            $query->execute(array($this->idUser));
            $arr = $query->fetch();
            $url = "/restore/?key=".$this->crypt->StrToHex($arr['date_restore'])."&hash=".$arr['hash'];
            $user = !empty($arr['first_name']) ? $arr['first_name'] : $arr['email'];
            $msg = $this->GetTplMailMsg($user, $arr['email'], $url, '/skins/tpl/mail/restore-pass.tpl.php');
            $this->mail = new Mailer($msg);
            $this->Mail("Восстановление аккаунта на GS11", $arr['email'], "noreply@gs11.ru", $this->mail);
            return true;
        }
        return false;

    }

    public function RestoreConfirmPhone()
    {
        if($this->_p['code-restore'] == $_SESSION['sms-code'])
        {
            $stmt = $this->conn->dbh->prepare("SELECT id FROM users WHERE phone = ?");
            $stmt->execute(array($_SESSION['sms-phone']));
            $res = $stmt->fetch(PDO::FETCH_OBJ);
            $_SESSION['restore-id-user'] = $res->id;
            $_SESSION['session-restore'] = true;
            return true;
        }
        return "code-false";
    }

    public function ExistEmailRestore($arrGet)
    {
        $stmt = $this->conn->dbh->prepare("SELECT id_user FROM users_restore WHERE date = ? AND hash = ?");
        $stmt->execute(array($this->crypt->HexToStr($arrGet['key']), $arrGet['hash']));
        $res = $stmt->fetch();
        return ($stmt->rowCount() == 1) ? $res['id_user'] : false;
    }

    public function ChangePassRestore()
    {
        if (strlen($this->_p['pass']) < 7)
            return "Пароль должен быть не менее 8 символов";
        else if ($this->_p['pass'] !== $this->_p['pass-conf'])
            return "Пароли не совподают";
        else if (empty($_SESSION['restore-id-user']))
            return "Внутренняя ошибка";
        else
        {
            $stmt = $this->conn->dbh->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute(array($_SESSION['restore-id-user']));
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if($res)
            {
                if(empty($_SESSION['exist-pass-restore-phone']))
                {
                    $stmt = $this->conn->dbh->prepare("UPDATE users_restore SET hash = 1 WHERE date = ? AND hash = ?");
                    $stmt->execute(array($this->crypt->HexToStr($_SESSION['restore-key']), $_SESSION['restore-hash']));
                }
                else
                {
                    $this->conn->dbh->exec("UPDATE users_restore SET hash = 1
                    WHERE TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(date))  <= 1 AND  hash = '".$_SESSION['hash-success']."'");
                }
                $stmt = $this->conn->dbh->prepare("UPDATE users SET pass = ? WHERE id = ?");
                $stmt->execute(array($this->hashPass->Hash($this->_p['pass']), $res['id']));
                $this->RedirectAuth($res);
            }
            return "Внутренняя ошибка";
        }
    }


    private function SetTableRestoreUsers($method, $hash)
    {
        $phone = (!empty($_SESSION['sms-phone'])) ? $_SESSION['sms-phone'] : "";
        $stmt = $this->conn->dbh->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
        $stmt->execute(array($phone, $this->email));
        $result = $stmt->fetch();
        $this->idUser = $result[0];
        $query = $this->conn->dbh->prepare("INSERT INTO users_restore SET id_user = ?, date = UNIX_TIMESTAMP(NOW()), method = ?, hash = ?, ip = ?");
        $query->execute(array($this->idUser, $method, $hash, $this->ipUser));
    }

    private function SendPhoneNotice()
    {
        $arr = $this->conn->dbh->query("SELECT phone FROM users WHERE phone <> '' LIMIT 1, 1")->fetchAll(PDO::FETCH_OBJ);
        $msg = "Приглашаем вас принять участие в турнире по игре DOTA 2";
        $msg = "Уважаемый Rom@n вы заняли почетное третье место в первой серии игр по турниру DOTA 2";
        $msg = "Уважаемый Игорь сегодня завершается конкурс по игре Diablo 3, от вас нет видеоролика. По всем вопросам вы мжете обратиться сюда vk.com/backahell, с уважением GS11.RU";
        //$msg = "Уважаемый DeneGniyBars вы нарушили правила, в связи с чем на следующий этап проходит ваш соперник. В будущем читайте внимательно правила. С уважением GS11";
        //$msg = "Уважаемый OmFg, вы должны сегодня отыграть с вашим соперником в течении 5 часов, если вы не отыграете и не отпишите администратору сайта, вы автоматически будете проигравшим. Если ваш соперник не появиться, отпишите администратору сайта, он вас продвинет на следующий раунд.";
        //$msg = "Уважаемый Haski, вы должны сегодня отыграть с вашим соперником в течении 5 часов, если вы не отыграете или не отпишите администратору сайта, вы автоматически будете проигравшим. Если ваш соперник не появиться, отпишите администратору сайта, он вас продвинет на следующий раунд.";
        //$msg = "Уважаемый FlexMix, вы должны сегодня отыграть с вашим соперником в течении 5 часов, если вы не отыграете и не отпишите администратору сайта, вы автоматически будете проигравшим. Если ваш соперник не появиться, отпишите администратору сайта, он вас продвинет на следующий раунд.";
        //$msg = "Уважаемый Пользователь вы должны сегодня отыграть с вашим соперником в течении 5 часов, если вы не отыграете и не отпишите администратору сайта, вы автоматически будете проигравшим. Если ваш соперник не появиться, отпишите администратору сайта, он вас продвинет на следующий раунд.";
        foreach($arr as $r)
        {
            $phone = $this->conn->dbh->query("SELECT * FROM send_notice WHERE phone = '".$r->phone."'")->rowCount();
            if(!$phone)
            {
                $resTypePhone = $this->sms->GetTypePhone($r->phone);
                ($resTypePhone['type'] == "russia") ? $this->sms->SendSmsRussia($r->phone, $msg) : $this->sms->SendSmsWorld($r->phone, $msg, 0);
                $this->conn->dbh->exec("INSERT INTO send_notice SET phone = '".$r->phone."', email = ''");
            }

        }
        echo "Готово";
    }
    private function SendEmailNotice()
    {
        $arr = $this->conn->dbh->query("SELECT email FROM users WHERE email <> '' LIMIT 8, 50")->fetchAll(PDO::FETCH_OBJ);
        foreach($arr as $r)
        {
            $email = $this->conn->dbh->query("SELECT * FROM send_notice WHERE email = '".$r->email."'")->rowCount();
            if(!$email)
            {
                $msg = $this->GetTplMailMsg(false, $r->email, false, '/skins/tpl/mail/notice-tournament.tpl.php');
                $this->mail = new Mailer($msg);
                $this->Mail("Турнир DOTA 2", $r->email, "noreply@gs11.ru", $this->mail);
            }
            $this->conn->dbh->exec("INSERT INTO send_notice SET email = '".$r->email."', phone = ''");

        }
        echo "Готово";

    }

}