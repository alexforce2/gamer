<?php
namespace application\modules\administration\auth;
use application\core\mvc\MainModel as MainModel;
use classes\HashPass;
class Model extends MainModel 
{    
    private $objBcrypt;
    public function __construct()
    {
        parent::__construct();
    }

    public function Auth()
    {
        $query = $this->conn->dbh->prepare("SELECT * FROM admin_users WHERE login = ?");
        $query->execute(array($this->_p['login']));
        $arrData = $query->fetch();
        if($query->rowCount() == 1)
           return ($this->ConfirmPass($arrData['pswd'])) ? $arrData : false;
        return false;
    }
    private function ConfirmPass($pass)
    {
        $this->objBcrypt = new HashPass();
        return $this->objBcrypt->Verify($this->_p['pass'], $pass);
    }
}
