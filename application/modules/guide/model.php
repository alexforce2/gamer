<?php
namespace application\modules\guide;
use application\core\mvc\MainModel;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();

    }

    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT pg.*, g.name, g.source_img_b, g.source_img_s, genre.name as genre  FROM main_page_games pg LEFT JOIN games g ON pg.id_game = g.id LEFT JOIN genre ON genre.id = g.genre_id WHERE title<>''")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetGame($id)
    {
        return $this->conn->dbh->query("SELECT pg.*, g.name, g.source_img_b, g.source_img_s, genre.name as genre  FROM main_page_games pg LEFT JOIN games g ON pg.id_game = g.id LEFT JOIN genre ON genre.id = g.genre_id WHERE pg.id = " . $id)->fetch(PDO::FETCH_OBJ);
    }
    public function GetGameImg($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games_screenshot WHERE id_main_page_game = " . $id)->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetGameRubric($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games_rubric WHERE id_main_page_game = " . $id)->fetchAll(PDO::FETCH_OBJ);
    }

}