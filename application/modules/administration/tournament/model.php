<?php
namespace application\modules\administration\tournament;
use application\core\mvc\MainModel;
use PDO;
use classes\render;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }


    public function GetData()
    {
        return $this->conn->dbh->query("SELECT * FROM heroes_games LIMIT 0, 500 ")->fetchAll(PDO::FETCH_ASSOC);
    }


    // начало управление турнирами
    public function ListTournaments()
    {
        return $this->conn->dbh->query("SELECT * FROM tournaments LIMIT 0, 500 ")->fetchAll(PDO::FETCH_OBJ);
    }
    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }


    public function GetTournament()
    {
        return $this->conn->dbh->query("SELECT * FROM tournaments WHERE id = " . (int)$this->_g['id'])->fetch(PDO::FETCH_OBJ);
    }

    public function AddTournament($params)
    {
        $startDateReg = strtotime($params['start_date_reg']);
        $endDateReg = strtotime($params['end_date_reg']);
        $startDate = strtotime($params['start_date']);
        $endDate = strtotime($params['end_date']);
        $query = $this->conn->dbh->prepare("INSERT INTO tournaments SET id_game = :id_game, state = :state, pay = :pay, header = :header, rules = :rules, video_rules = :video_rules,
                                            start_date_reg = :start_date_reg, end_date_reg = :end_date_reg, start_date = :start_date, end_date = :end_date,
                                            title = :title, keywords = :keywords, description = :description, source_img = :source_img");

        $query->bindParam(":id_game", $params['id_game'], PDO::PARAM_INT);
        $query->bindParam(":start_date_reg", $startDateReg, PDO::PARAM_INT);
        $query->bindParam(":end_date_reg", $endDateReg, PDO::PARAM_INT);
        $query->bindParam(":start_date", $startDate, PDO::PARAM_INT);
        $query->bindParam(":end_date", $endDate, PDO::PARAM_INT);
        $query->bindParam(":state", $params['state'], PDO::PARAM_INT);
        $query->bindParam(":pay", $params['pay'], PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":rules", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":video_rules", $params['video_rules'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id'], "tournaments");
    }

    public function EditTournament($params)
    {
        $startDateReg = strtotime($params['start_date_reg']);
        $endDateReg = strtotime($params['end_date_reg']);
        $startDate = strtotime($params['start_date']);
        $endDate = strtotime($params['end_date']);
        $query = $this->conn->dbh->prepare("UPDATE tournaments SET id_game = :id_game, state = :state, pay = :pay, header = :header, rules = :rules, video_rules = :video_rules,
                                            start_date_reg = :start_date_reg, end_date_reg = :end_date_reg, start_date = :start_date, end_date = :end_date,
                                            title = :title, keywords = :keywords, description = :description, source_img = :source_img WHERE id=:id");
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":id_game", $params['id_game'], PDO::PARAM_INT);
        $query->bindParam(":start_date_reg", $startDateReg, PDO::PARAM_INT);
        $query->bindParam(":end_date_reg", $endDateReg, PDO::PARAM_INT);
        $query->bindParam(":start_date", $startDate, PDO::PARAM_INT);
        $query->bindParam(":end_date", $endDate, PDO::PARAM_INT);
        $query->bindParam(":state", $params['state'], PDO::PARAM_INT);
        $query->bindParam(":pay", $params['pay'], PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":rules", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":video_rules", $params['video_rules'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id'], "tournaments");
    }



    /***********<< Начало управления победителями турниров >>***********/
    public function WorkFile($params,$path,$bool)
    {
        if($bool == false)
        {
            unlink($this->rootDir . $path. $params);
        }else
        {
            $file = basename($params);
            copy($this->rootDir. $params,$this->rootDir. $path. $file);
            unlink($this->rootDir . $params);
        }
    }
    public function ListWinnerTournament()
    {
        return $this->conn->dbh->query("SELECT id, header  FROM tournaments")->fetchAll(PDO::FETCH_OBJ);
    }
    public function ListAllUsers()
    {
        return $this->conn->dbh->query("SELECT u.nick, u.id, w.winner  FROM history_members_tournaments mt
                                        LEFT JOIN users u ON u.id = mt.id_user
                                        LEFT JOIN winners w ON mt.id_user = w.winner AND mt.id_tournament = w.id_tournament WHERE mt.id_tournament = " . (int)$this->_g['id'])->fetchAll(PDO::FETCH_OBJ);
    }
    public function ListWinnerUsers()
    {
        return $this->conn->dbh->query("SELECT w.place, w.id as id_row, u.nick FROM tournaments t
                                        LEFT JOIN winners w ON t.id = w.id_tournament
                                        LEFT JOIN users u ON w.winner = u.id WHERE w.id_tournament =" . (int)$this->_p['id'])->fetchAll(PDO::FETCH_OBJ);
    }
    public function DeleteWinner()
    {
        return $this->conn->dbh->query("DELETE FROM winners WHERE id = ".$this->_g['edit']);
    }

    public function GetEditInfoWinner()
    {
        if(!empty($this->_g['edit']))
        {
            return $this->conn->dbh->query("SELECT * FROM winners WHERE id = ". $this->_g['edit'])->fetch(PDO::FETCH_OBJ);
        }
    }
    public function AddWinner($params)
    {
        $this->WorkFile($params['video-link'],"storage/winner/tournament-winner-video/",true);
        $this->WorkFile($params['audio-link'],"storage/winner/tournament-winner-audio/",true);
       $rowCount = $this->conn->dbh->query("SELECT winner FROM winners WHERE id = ".(int)$params['id_row'])->rowCount();
       if($rowCount == 1)
       {
           $query = "UPDATE winners SET id_tournament = :id_tournament, winner = :id_winner, text = :text, title = :title, keywords = :keywords, description = :description, place = :place, video_link = :video_link, audio_link = :audio_link WHERE id = ".(int)$params['id_row'];
       }else{
         $query = "INSERT INTO winners SET id_tournament = :id_tournament,  winner = :id_winner, text = :text, title = :title, keywords = :keywords, description = :description,  place = :place, video_link = :video_link, audio_link = :audio_link";
       }

        $stmt = $this->conn->dbh->prepare($query);
        $stmt->bindParam(":id_tournament", $params['id_tournament'], PDO::PARAM_INT);
        $stmt->bindParam(":id_winner", $params['id_winner'], PDO::PARAM_INT);
        $stmt->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $stmt->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $stmt->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $stmt->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $stmt->bindParam(":place", $params['place'], PDO::PARAM_INT);
        if(!empty($params['deleted-video-link']) || !empty($params['deleted-audio-link']))
        {
            if(!empty($params['video-link']) || !empty($params['audio-link'])){
                $stmt->bindParam(":video_link", basename($params['video-link']), PDO::PARAM_STR);
                $stmt->bindParam(":audio_link", basename($params['audio-link']), PDO::PARAM_STR);
            }else{
                $var = '';
                $stmt->bindParam(":video_link", $var, PDO::PARAM_STR);
                $stmt->bindParam(":audio_link", $var, PDO::PARAM_STR);
            }
        }else{
            $stmt->bindParam(":video_link", basename($params['video-link']), PDO::PARAM_STR);
            $stmt->bindParam(":audio_link", basename($params['audio-link']), PDO::PARAM_STR);
        }
        $this->WorkFile($params['deleted-video-link'],"storage/winner/tournament-winner-video/",false);
        $this->WorkFile($params['deleted-audio-link'],"storage/winner/tournament-winner-audio/",false);
        $stmt->execute();
    }
    /***********************  Конец управления победителями турниров***************************/



    /*public function Add($params)
    {
        $query = $this->conn->dbh->prepare("INSERT INTO heroes_games SET id_game = 186, name = :name, description = :description, source_img = :source_img");
        $query->bindParam(":name", $params['name'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        return $this->GetById($id);
    }

    public function Edit($params)
    {
        $query = $this->conn->dbh->prepare("UPDATE heroes_games SET id_game = 186, name = :name, description = :description, source_img = :source_img WHERE id=:id");
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":name", $params['name'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id']);
    }*/





    public function GetById($id, $table)
    {
        return $this->conn->dbh->query("SELECT *  FROM ".$table." WHERE id=" . $id)->fetch();
    }

    public function Delete($id)
    {
        $query = $this->conn->dbh->prepare("DELETE FROM heroes_games WHERE id=:id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}