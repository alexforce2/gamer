<?php
namespace application\core\db;
use PDO;
class MyPDO extends PDO
{
    public $countQuery = 0;
    public function __construct($dsn, $user, $pass, $options)
    {
        parent::__construct($dsn, $user, $pass, $options);
    }
    public function prepare($statement, $driver_options = array()) 
    {
        $this->countQuery++;
        return parent::prepare($statement, $driver_options);
    }
    public function query($statement) 
    {
        $this->countQuery++;
        return parent::query($statement);
    }
}
