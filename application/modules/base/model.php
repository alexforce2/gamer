<?php
namespace application\modules\base;
use application\core\mvc\MainModel;
use PDO;
use classes\OftenFunctions;

class Model extends MainModel
{

    public function __construct()
    {
        parent::__construct();
    }

    //Функия принимает массив(из 3-х ячеек) и возвращает Юникс метку времени.
    public function MakeUnixTime($date)
    {
        if (is_array($date)) {
            $date[0] = intval($date[0]);
            $date[1] = intval($date[1]);
            $date[2] = intval($date[2]);
            $date[0] = (($date[0] < 0) || ($date[0] > 31)) ? 1 : $date[0];
            $date[1] = (($date[1] < 0) || ($date[1] > 12)) ? 1 : $date[1];
            $date[2] = (($date[2] < 1974) || ($date[2] > 2014)) ? 2014 : $date[2];
            return mktime(12, 0, 0, $date[1], $date[0], $date[2]);
        } else {
            return 0;
        }
    }

    //Проверка дат начала и конца прохождения игры, также относительно текущего времени согласно логики
    public function CheckGameTimePassing(&$start, &$end, $now=PHP_INT_MAX)
    {
        if($start > $now)
            $start = $now;
        if($end > $now)
            $end = $now;
        if($start > $end && $end !== 0){
            $startCopy=$start;
            $start = $end;
            $end = $startCopy;
        }
    }

    //Добавление пройденной игры в БД
    public function AddCompletedGame()
    {
        $gameStartDate = $this->_p['game-start-date'];
        $gameEndDate = $this->_p['game-end-date'];
        $gameStartDate = (((int)$gameStartDate) > 0) ? $this->MakeUnixTime(explode("-", $gameStartDate, 3)) : 0;
        $gameEndDate = (((int)$gameEndDate) > 0) ? $this->MakeUnixTime(explode("-", $gameEndDate, 3)) : 0;
        $idLevelGame = explode("\$", $this->_p['game-level']);
        $idGame = (isset($idLevelGame[1]))?(int)$idLevelGame[1]:0;
        $idLevel = (int)$idLevelGame[0];
        $questCount = (int)$this->_p['quest-qount'];
        $idGamePassing = (int)$this->_p['game-passing'];
        $idUser = (int)$_SESSION['user-data']['id'];
        $gameDescription = $this->_p['game-description'];
        $userRating = floatval($this->_p['game-rating']);
        $postDate = time();
        $this->CheckGameTimePassing($gameStartDate, $gameEndDate, $postDate);
        $checkAddedGame = $this->conn->dbh->query("SELECT id_game FROM user_completed_games WHERE id_game=" . $idGame . " AND id_user=" . $idUser)->fetch(PDO::FETCH_ASSOC);
        $checkGame = $this->conn->dbh->query("SELECT name FROM games WHERE id = ".$idGame)->fetch(PDO::FETCH_ASSOC);
        if ($checkAddedGame === false && $checkGame !== false) {
            $stmt = $this->conn->dbh->prepare("INSERT INTO user_completed_games (id_user, id_game, id_level, about_game, start_date, end_date, post_date, num_quest, id_type_completed_game, user_rating)
                                                        VALUES(:idUser, :idGame, :idLevel, :gameDescription, :startDate, :endDate, :postDate, :questCount, :idGamePassing, :userRating)");
            $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
            $stmt->bindParam(":idLevel", $idLevel, PDO::PARAM_INT);
            $stmt->bindParam(":startDate", $gameStartDate, PDO::PARAM_INT);
            $stmt->bindParam(":endDate", $gameEndDate, PDO::PARAM_INT);
            $stmt->bindParam(":postDate", $postDate, PDO::PARAM_INT);
            $stmt->bindParam(":questCount", $questCount, PDO::PARAM_INT);
            $stmt->bindParam(":idGamePassing", $idGamePassing, PDO::PARAM_INT);
            $stmt->bindParam(":userRating", $userRating, PDO::PARAM_INT);
            $stmt->bindParam(":gameDescription", $gameDescription, PDO::PARAM_STR);
            $stmt->execute();
            $updateRating = $this->conn->dbh->prepare("UPDATE games_rating SET rating = rating + :rating, suffrage_count=suffrage_count+1
                                                        WHERE id_game=:idGame");
            $updateRating->bindParam(":rating", $userRating, PDO::PARAM_INT);
            $updateRating->bindParam(":idGame", $idGame, PDO::PARAM_INT);
            $updateRating->execute();
            return ("addGame");
        } elseif ($checkAddedGame !== false) {
            return ("isGame");
        } elseif ($checkGame === false) {
            return ("notGame");
        }
    }

    //Изменение ранее добавленной игры
    public function UpdateAddedGame()
    {
        $gameStartDate = $this->_p['game-start'];
        $gameEndDate = $this->_p['game-end'];
        $gameStartDate = (((int)$gameStartDate) > 0) ? $this->MakeUnixTime(explode("-", $gameStartDate, 3)) : 0;
        $gameEndDate = (((int)$gameEndDate) > 0) ? $this->MakeUnixTime(explode("-", $gameEndDate, 3)) : 0;
        $now = time();
        $this->CheckGameTimePassing($gameStartDate, $gameEndDate, $now);
        $idGame = (int)$this->_p['game-id'];
        $idLevel = (int)$this->_p['level-id'];
        $questCount = (int)$this->_p['quest-qount'];
        $idGamePassing = (int)$this->_p['game-passing'];
        $idUser = (int)$_SESSION['user-data']['id'];
        $gameDescription = $this->_p['game-description'];
        $stmt = $this->conn->dbh->prepare("UPDATE user_completed_games
                                            SET id_level=:idLevel, id_type_completed_game=:idGamePassing, num_quest=:questCount, start_date=:startDate, end_date=:endDate, about_game=:gameDescription
                                            WHERE id_user=:idUser AND id_game=:idGame");
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $stmt->bindParam(":idLevel", $idLevel, PDO::PARAM_INT);
        $stmt->bindParam(":startDate", $gameStartDate, PDO::PARAM_INT);
        $stmt->bindParam(":endDate", $gameEndDate, PDO::PARAM_INT);
        $stmt->bindParam(":questCount", $questCount, PDO::PARAM_INT);
        $stmt->bindParam(":idGamePassing", $idGamePassing, PDO::PARAM_INT);
        $stmt->bindParam(":gameDescription", $gameDescription, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    }


    //Получение пройденных игр из БД
    public function GetUserCompletedGames($idUser=false)
    {
        $idUser = (!$idUser) ? (int)$_SESSION['user-data']['id'] : (int)$idUser;
        $sql = $this->conn->dbh->prepare("SELECT ucg.num_quest, ucg.start_date, ucg.end_date, ucg.post_date, ucg.about_game,
                                            games.name as game, games.source_img_b, games.source_img_s, games.id,
                                            level.name as level, level.description as level_description,
                                            tcg.name as type_complete_game,
                                            genre.name as genre,
                                            users.nick, users.id as id_user
                                        FROM user_completed_games ucg
                                        LEFT JOIN users ON users.id=ucg.id_user
                                        LEFT JOIN games ON games.id=ucg.id_game
                                        LEFT JOIN level ON level.id=ucg.id_level
                                        LEFT JOIN type_complete_game tcg ON tcg.id=ucg.id_type_completed_game
                                        LEFT JOIN genre ON genre.id=games.genre_id
                                        WHERE users.id=:idUser ORDER BY games.name");
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //Получение информации о пройденной игре пользователя по ИД игры
    public function GetGameView($idGame, $idUser=false)
    {
        /*for($i=1; $i<=189; $i++){
            $r = floatval(mt_rand(30, 49).".".mt_rand(3, 8));
            $checkGame = $this->conn->dbh->query("UPDATE games_rating SET rating = ".$r." , suffrage_count = 10  WHERE id_game= ".$i);
        }*/
        $idUser = (!$idUser) ? (int)$_SESSION['user-data']['id'] : (int)$idUser;
        $idGame = (int)$idGame;
        $sql = $this->conn->dbh->prepare("SELECT ucg.num_quest, ucg.start_date, ucg.end_date, ucg.post_date, ucg.about_game, ucg.id_game, ucg.id as id_ucg, ucg.id_user,
                                            games.name as game, games.source_img_b, games.source_img_s,
                                            level.name as level, level.description as level_description,
                                            tcg.name as type_complete_game,
                                            genre.name as genre
                                        FROM user_completed_games ucg
                                        LEFT JOIN users ON users.id=ucg.id_user
                                        LEFT JOIN games ON games.id=ucg.id_game
                                        LEFT JOIN level ON level.id=ucg.id_level
                                        LEFT JOIN type_complete_game tcg ON tcg.id=ucg.id_type_completed_game
                                        LEFT JOIN genre ON genre.id=games.genre_id
                                        WHERE users.id=:idUser AND ucg.id_game=:idGame ORDER BY game ");
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    //Получение игр из БД похоже это нигде не используется
    public function GetGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }

    //Получение массива типов прохождения игр
    public function GetTypeCompleteGame()
    {
        $sql = $this->conn->dbh->prepare("SELECT * FROM type_complete_game");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //Загрузка изображений на сервер в папку пользователя.
    public function UploadUserGameImg()
    {
        if(isset($this->_p['source_img'])){
            $idUser = (int)$_SESSION['user-data']['id'];
            $idGame = (int)$this->_p['game-id'];
            for($i=0; $i < count($this->_p['source_img']); $i++){
                $imgS = "storage/user_img/" . $_SESSION['user-data']['path'] . "/" . basename($this->_p['source_img'][$i]);
                $imgB = str_replace("_s", "_b", $imgS);
                $oldImgS = $this->_p['source_img'][$i];
                $oldImgB = str_replace("_s", "_b", $oldImgS);
                if(rename($oldImgS, $imgS) && rename($oldImgB, $imgB)){
                    $imgS = "/".$imgS;
                    $imgB = "/".$imgB;
                    $sql = $this->conn->dbh->prepare("INSERT INTO user_game_img SET id_user=:idUser, id_game=:idGame, game_img_s=:imgS, game_img_b=:imgB");
                    $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
                    $sql->bindParam(":idGame", $idGame, PDO::PARAM_INT);
                    $sql->bindParam(":imgS", $imgS, PDO::PARAM_STR);
                    $sql->bindParam(":imgB", $imgB, PDO::PARAM_STR);
                    $sql->execute();
                }
            }
        }
    }

    //Получение массива картинок пройденной игры пользователя
    public function GetUserImgGame($idGame)
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $idGame = (int)$idGame;
        $sql = $this->conn->dbh->prepare("SELECT id, game_img_b, game_img_s FROM user_game_img WHERE id_user=:idUser AND id_game=:idGame");
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $sql->execute();
        return  $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //Удаление картинок пройденной игры, загруженных пользователем
    public function RemoveUserImgGame($deleteImg)
    {
        // todo: вынести в из цикла запрос
        if(count($deleteImg) > 0){
            $imagesId="";
            $countImg=0;
            foreach($deleteImg as $imageId){
                $countImg++;
                list($id, $image)=explode("$", $imageId);
                $imgBInfo = pathinfo($image);
                $imgB = substr($imgBInfo['dirname'], 1)."/".str_replace("_s", "_b", $imgBInfo['filename']).".".$imgBInfo['extension'];
                $removeImgB = unlink($imgB);
                $removeImgS = unlink(substr($image, 1));
                if($removeImgB && $removeImgS ){
                    if($countImg === 1){
                        $imagesId .= $id;
                    }else{
                        $imagesId .= ",".$id;
                    }
                }
            }
            $sql = $this->conn->dbh->prepare("DELETE FROM user_game_img WHERE FIND_IN_SET(id, :id)");
            $sql->bindParam(":id", $imagesId, PDO::PARAM_STR);
            $sql->execute();
        }
    }

    //Получение массива уровней сложности игры по её ИД
    public function GetLevels($idGame)
    {
        return $this->conn->dbh->query("SELECT id, name FROM level WHERE id_game=".$idGame)->fetchAll(PDO::FETCH_ASSOC);
    }

    //Автокомплит названия игры при наборе в модальной форме
    public function GetGame()
    {
        $result = [];
        $query = "%{$this->_p['query']}%";
        $idUser = (int)$_SESSION['user-data']['id'];
        $limit = !empty($this->_p['limit']) ? (int)$this->_p['limit'] : 10;
        if (!empty($query)) {
            $sql = $this->conn->dbh->prepare('SELECT DISTINCT g1.name FROM games g1 WHERE NOT EXISTS (SELECT g2.name FROM games g2
                                                LEFT JOIN user_completed_games ucg ON ucg.id_game=g2.id
                                                WHERE ucg.id_user=:idUser AND g1.name=g2.name) AND g1.name LIKE :game ORDER BY g1.name LIMIT :limit');
            $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $sql->bindParam(":game", $query, PDO::PARAM_STR);
            $sql->bindParam(":limit", $limit, PDO::PARAM_INT);
            $sql->execute();
            foreach ($sql as $value) {
                $result['suggestions'][] = $value[0];
            }
        }
        return json_encode($result);
    }

    //Получение уровней сложности, в зависимости от игры
    public function GetGameLevel()
    {
        $result = array();
        $game = $this->_p['game'];
        if (!empty($game)) {
            $sql = $this->conn->dbh->prepare("SELECT l.name, l.id as id_level, g.id as id_game, gr.rating, gr.suffrage_count FROM `level` l
            LEFT JOIN `games` g ON g.id=l.id_game
            LEFT JOIN `games_rating` gr ON gr.id_game = l.id_game
            WHERE g.name=:game");
            $sql->bindParam(":game", $game, PDO::PARAM_STR);
            $sql->execute();
            foreach ($sql as $level) {
                $result[0][] = $level['name'] . "$" . $level['id_level'] . "$" . $level['id_game'];
            }
            $result[1]['rating'] = $level['rating'];
            $result[1]['suffrage_count'] = $level['suffrage_count'];
            $result[1]['user_rating'] = $this->GetUserGameRating($level[2]);
            //$test = $this->CheckAddedGames($level[2]);
            //$result[] = $this->GetRatingInfoByGame($level[2]);
            //$result[] = $this->CheckAddedGames($level[2]);

        }
        return json_encode($result);
    }


    //Метод проверки наличия игры в таблеце "Пройденных игр"
    public function CheckAddedGames($idGame)
    {
        $idGame = (int)$idGame;
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT id_game FROM user_completed_games WHERE id_game = :idGame AND id_user= :idUser");
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $stmt->execute();
        return (count($stmt->fetchAll())>0) ? "true" : "false";
    }

    //Возвращает инфо о голосе юзера, о рейтинге игры
    public function GetUserGameRating($idGame)
    {
        $idGame = (int)$idGame;
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT user_rating FROM user_completed_games WHERE id_game = :idGame AND id_user= :idUser");
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result == false) ? false : $result['user_rating'];
    }

    public function GetUsersCompletedGame()
    {
        $idUser = (int)$_SESSION['user-data']['id'];
        $sql = $this->conn->dbh->prepare("SELECT DISTINCT u.id, u.nick FROM users u
                                            LEFT JOIN user_completed_games ucg ON ucg.id_user=u.id
                                            LEFT JOIN games g ON g.id=ucg.id_game
                                            WHERE ucg.id_user <> :idUser AND u.complete_games>0 ORDER BY u.nick");
        $sql->bindparam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Комментарии начало */
    public function AddComment()
    {
        // todo сделать проверку на существование элемента и не соответствии текущему user
        // todo сделать проверку на id турнира
        $test=15;
        $stmt = $this->conn->dbh->prepare("INSERT INTO comments_tournament (`date`,`id_user`,`id_user_answer`, `comment`, `id_section`) VALUES(UNIX_TIMESTAMP(NOW()), :id_user, :id_user_answer, :comment, :id_section)");
        $stmt->bindParam(":id_user", $_SESSION['user-data']['id'], PDO::PARAM_INT);
        $stmt->bindParam(":id_user_answer", $_POST['id-user-answer'], PDO::PARAM_INT);
        $stmt->bindParam(":comment", $_POST['comment'], PDO::PARAM_STR);
        $stmt->bindParam(":id_section", $test, PDO::PARAM_INT);
        $stmt->execute();
        $id = $this->conn->dbh->lastInsertId();
        return json_encode($this->LastComment($id));
    }

    public function ListComments()
    {
        $result = $this->conn->dbh->query("SELECT  c.*, u.nick, u.img_avatar, u2.nick as nick_answer, u2.img_avatar as img_avatar_answer  FROM comments_tournament c
                                            LEFT JOIN users u ON c.id_user = u.id
                                            LEFT JOIN users u2 ON c.id_user_answer = u2.id WHERE id_section = 15 LIMIT 0, 1000")->fetchAll(PDO::FETCH_OBJ);
        return json_encode($result);
    }

    public function RemoveComment()
    {
        $this->conn->dbh->query("DELETE FROM comments_tournament WHERE id = ".(int)$_POST['id']. " AND id_user = ". $_SESSION['user-data']['id'] . " AND id_section = 15");
    }

    public function MarkSpam()
    {
        $test=15;
        $stmt = $this->conn->dbh->exec("INSERT INTO spam_materials (date, id_user, id_comment, id_section, comment_product) VALUES (UNIX_TIMESTAMP(NOW()), :id_user, :id_comment, :id_section, :comment_product)");
        $stmt->bindParam(":id_user", $_POST['id-user'], PDO::PARAM_INT);
        $stmt->bindParam(":id_comment", $_POST['id-comment'], PDO::PARAM_INT);
        $stmt->bindParam(":id_section", $test, PDO::PARAM_INT);
        $stmt->bindParam(":comment_product", $_POST['comment-product'], PDO::PARAM_INT);
        $stmt->execute();
    }

    private function LastComment($id)
    {
        return $this->conn->dbh->query("SELECT c.*, u.nick, u.img_avatar, u2.nick as nick_answer, u2.img_avatar as img_avatar_answer  FROM comments_tournament c
                                            LEFT JOIN users u ON c.id_user = u.id
                                            LEFT JOIN users u2 ON c.id_user_answer = u2.id WHERE c.id = ". (int)$id. " AND id_section = 15")->fetchAll(PDO::FETCH_OBJ);
    }

    /* Комментарии Конец */


    /*public function GetUsersCompletedGames($idUser)
    {
        $idUser = (int)$idUser;
        $sql = $this->conn->dbh->prepare("SELECT ");
        $sql->bindparam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }*/
}

