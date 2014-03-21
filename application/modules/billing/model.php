<?php
namespace application\modules\billing;
use application\core\mvc\MainModel;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function Payment($pay, $sum, $description)
    {
        $stmt = $this->conn->dbh->prepare("INSERT INTO payment SET id_user = :id, pay = :pay, `sum` = :sum, date = UNIX_TIMESTAMP(NOW()), description = :description");
        $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
        $stmt->bindParam(":pay", $pay, PDO::PARAM_INT);
        $stmt->bindParam(":sum", $sum, PDO::PARAM_INT);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->execute();
    }
    public function PayUser()
    {
        $stmt = $this->conn->dbh->prepare("SELECT p.*, u.balance FROM payment p LEFT JOIN users u ON u.id = p.id_user WHERE p.pay = :pay AND p.confirm = 0");
        $stmt->bindParam(":pay", $_REQUEST['InvId'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function UpdatePay($r, $balance)
    {
        $stmt = $this->conn->dbh->prepare("UPDATE payment SET confirm = 1 WHERE pay = :pay");
        $stmt->bindParam(":pay", $r->pay, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $this->conn->dbh->prepare("UPDATE users SET balance = :balance WHERE id = :id");
        $stmt->bindParam(":balance", $balance, PDO::PARAM_INT);
        $stmt->bindParam(":id", $r->id_user, PDO::PARAM_INT);
        $stmt->execute();        
    }

    public function getPaymentHistoryByUserId ($id)
    {
        $stmt = $this->conn->dbh
            ->prepare("SELECT `id`, `date`, `description`, `sum`
                        FROM `transactions_payments` WHERE `id_user` = :id
                        ORDER BY `id` DESC");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function SetTariff()
    {
        // todo необходимо разработать систему для списания (таблица)
        // todo уведомление о том что произошла покупка тарифа
        if($this->_p['tariff'] == 1 || $this->_p['tariff'] == 2)
        {
            if($this->_p['tariff'] == 1)
            {
                $sum = 50;
                $description = "Подключение тарифа группа С";
            }

            if($this->_p['tariff'] == 2)
            {
                $sum = 150;
                $description = "Подключение тарифа группа X";
            }
            $sumTP = "-".$sum;
            $sum = (int)$_SESSION['user-data']['balance'] - $sum;
            if($sum >= 0)
            {

                $stmt = $this->conn->dbh->prepare("INSERT INTO transactions_payments SET `id_user` = :id, date = UNIX_TIMESTAMP(NOW()), description = :description, `sum` = :sum, `group` = :group");
                $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
                $stmt->bindParam(":description", $description, PDO::PARAM_STR);
                $stmt->bindParam(":sum", $sumTP, PDO::PARAM_STR);
                $stmt->bindParam(":group", $this->_p['tariff'], PDO::PARAM_INT);
                $stmt->execute();
                $stmt = $this->conn->dbh->prepare("UPDATE users SET `group` = :group, balance = :balance WHERE id = :id");
                $stmt->bindParam(":id", $_SESSION['user-data']['id'], PDO::PARAM_INT);
                $stmt->bindParam(":group", $this->_p['tariff'], PDO::PARAM_INT);
                $stmt->bindParam(":balance", $sum, PDO::PARAM_INT);
                $stmt->execute();
                $this->GetRefreshDataUser();
            }
            else
            {
                echo "На вашем счёте недостаточно средств";
            }

        }
        else
        {
            echo "Платёж не может быть проведён";
        }

    }
}

