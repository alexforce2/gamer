<?php
namespace application\modules\administration\press;
use application\core\mvc\MainModel;
use classes\render;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ListNews()
    {
        return $this->FetchObj("SELECT id, date, header FROM news ORDER BY date DESC LIMIT 0, 50 ");
    }

    public function AddNews()
    {
        $img = $this->MoveImg();
        $query = $this->conn->dbh->prepare("INSERT INTO news SET date = :date, header = :header, short = :short, text = :text, title = :title, description = :description, keywords = :keywords, img = :img");
        $parts = explode('.', $this->_p['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $this->_p['header'], PDO::PARAM_STR);
        $query->bindParam(":short", $this->_p['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $this->_p['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $this->_p['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $this->_p['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $this->_p['keywords'], PDO::PARAM_STR);
        $query->bindParam(":img", $img, PDO::PARAM_STR);
        $query->execute();
    }

    public function EditNews()
    {
        $img = $this->MoveImg();
        $query = $this->conn->dbh->prepare("UPDATE news SET date = :date, header = :header, short = :short, text = :text, title = :title, description = :description, keywords = :keywords, img = :img WHERE id=:id");
        $parts = explode('.', $this->_p['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":id", $this->_p['id'], PDO::PARAM_INT);
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $this->_p['header'], PDO::PARAM_STR);
        $query->bindParam(":short", $this->_p['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $this->_p['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $this->_p['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $this->_p['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $this->_p['keywords'], PDO::PARAM_STR);
        $query->bindParam(":img", $img, PDO::PARAM_STR);
        $query->execute();
    }

    private function MoveImg()
    {
        // todo: исправить данный блок
        $arrImg = explode("/", $this->_p['img']);
        $img = "/storage/press/news/".$arrImg[2];
        $img = empty($arrImg[2]) ? "" : $img;
        if(!empty($arrImg[2]))
            rename($this->rootDir.$this->_p['img'], $this->rootDir. "storage/press/news/".$arrImg[2]);
        return $img;
    }

    public function GetNews()
    {
        return $this->FetchObj("SELECT *  FROM news WHERE id = :id", array(":id" => $this->_g["id"]));
    }

    public function Delete($id)
    {
        $query = $this->conn->dbh->prepare("DELETE FROM news WHERE id=:id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}