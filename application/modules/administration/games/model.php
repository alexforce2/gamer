<?php
namespace application\modules\administration\games;
use application\core\mvc\MainModel as MainModel;
use classes\render as Render;
use PDO;

class Model extends MainModel
{
    private $game, $sourceImgType;
    public function __construct()
    {
        parent::__construct();
    }

    /* Начало добавление списка игр*/
    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    /* Конец добавление списка игр*/

    public function SearchGameAjax()
    {
        $search = "%".$this->_p['search-game']."%";
        $stmt = $this->conn->dbh->prepare("SELECT * FROM games WHERE name LIKE :game");
        $stmt->bindParam(":game", $search, PDO::FETCH_ASSOC);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function GetGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM games WHERE id=".$id)->fetch(PDO::FETCH_OBJ);
    }
    public function GetDifficulty($id)
    {
        return $this->conn->dbh->query("SELECT * FROM level WHERE id_game=".$id. " ORDER BY id ASC")->fetchAll(PDO::FETCH_OBJ);
    }

    /* Начало работа со справочниками по играм */
    public function ListGenre()
    {
        $stmt = $this->conn->dbh->prepare("SELECT * FROM genre");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function AddMainGame()
    {
        $sourceImgB = $this->MoveImg($this->_p['source_img_b'], true);
        $sourceImgS = $this->MoveImg($this->_p['source_img_s'], true);
        $stmt = $this->conn->dbh->prepare("INSERT INTO games SET name = :name, genre_id = :genre_id, source_img_b = :source_img_b, source_img_s = :source_img_s");
        $stmt->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $stmt->bindParam(":genre_id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->bindParam(":source_img_b", $sourceImgB, PDO::PARAM_STR);
        $stmt->bindParam(":source_img_s", $sourceImgS, PDO::PARAM_STR);
        $stmt->execute();
        $id = $this->conn->dbh->lastInsertId();
        $this->AddDifficulty($id);
        $this->AddMainPageGuide($id);
    }

    private function AddMainPageGuide($id)
    {
        $this->conn->dbh->exec("INSERT INTO main_page_games (date, id_game) VALUES (UNIX_TIMESTAMP(NOW()), {$id})");
    }

    public function UpdateMainGame()
    {
        // проверять каждое поле не поменялось ли, а дальше если поменялось обновлять его если такого поля и вовсе нету, то добавить
        $this->DeleteDifficulty();
        $this->UpdateDifficulty();
        $this->game = $this->GetGame($this->_p['id']);
        $sourceImgB = $this->WorkImg($this->game->source_img_b, $this->_p['source_img_b']);
        $sourceImgS = $this->WorkImg($this->game->source_img_s,  $this->_p['source_img_s']);
        $stmt = $this->conn->dbh->prepare("UPDATE games SET name = :name, genre_id = :genre_id, source_img_b = :source_img_b, source_img_s = :source_img_s WHERE id = :id");
        $stmt->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $stmt->bindParam(":genre_id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->bindParam(":source_img_b", $sourceImgB, PDO::PARAM_STR);
        $stmt->bindParam(":source_img_s", $sourceImgS, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->_p['id'], PDO::PARAM_INT);
        $stmt->execute();
    }


    private function UpdateDifficulty()
    {
        if(count($this->_p['name-difficulty']) == count($this->_p['description-difficulty']))
        {
            $arrLevel = $this->GetDifficulty($this->_p['id']);
            for($i = -1; $i < count($this->_p['name-difficulty']); ++$i)
            {
                $name = ($arrLevel[$i]->name == $this->_p['name-difficulty'][$i]) ? $arrLevel[$i]->name : $this->_p['name-difficulty'][$i];
                $description = ($arrLevel[$i]->description == $this->_p['description-difficulty'][$i]) ? $arrLevel[$i]->description : $this->_p['description-difficulty'][$i];
                if($name != "" || $description != "")
                {
                    $stmt = $this->conn->dbh->prepare("UPDATE level SET `name` = :name, description = :description WHERE id_game = :id_game AND id = :id");
                    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                    $stmt->bindParam(":description", $description, PDO::PARAM_STR);
                    $stmt->bindParam(":id_game", $this->_p['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":id", $arrLevel[$i]->id, PDO::PARAM_INT);
                    $stmt->execute();
                }
                if($i == count($arrLevel)-1)
                    break;
            }
            if(count($this->_p['name-difficulty']) > count($arrLevel))
            {
                $this->AddDifficulty($this->_p["id"], ++$i);
            }
        }
    }

    private function DeleteDifficulty()
    {
        if(!empty($this->_p['delete-field']))
        {
            $strId = "";
            foreach($this->_p['delete-field'] as $r)
            {
                $strId .= $r.",";
            }
            $arrId = substr($strId, 0, strlen($strId) - 1);
            $stmt = $this->conn->dbh->prepare("DELETE FROM level WHERE FIND_IN_SET(id, :id)");
            $stmt->bindParam(":id", $arrId, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    private function AddDifficulty($id, $i = 0)
    {
        if(count($this->_p['name-difficulty']) == count($this->_p['description-difficulty']))
        {

            for($i; $i < count($this->_p['name-difficulty']); $i++)
            {
                if($this->_p['name-difficulty'][$i] != "" || $this->_p['name-difficulty'][$i] != "")
                {
                    $stmt = $this->conn->dbh->prepare("INSERT INTO level SET id_game = :id_game, name = :name, description = :description");
                    $stmt->bindParam(":id_game", $id, PDO::PARAM_INT);
                    $stmt->bindParam(":name", $this->_p['name-difficulty'][$i], PDO::PARAM_STR);
                    $stmt->bindParam(":description", $this->_p['description-difficulty'][$i], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    }

    private function PrepareDifficulty()
    {
        /*foreach($this->_p['name-difficulty'] as $name)
        {
            foreach($this->_p['description-difficulty'] as $description)
            {
                 $param = $name . "#" .  $description;
            }
            $arr[] = $param;
        }
        return $arr;*/
    }

    private function WorkImg($SourceImgType,  $file)
    {
        $this->sourceImgType = $SourceImgType;
        if($SourceImgType != $file)
        {
            $sourceImg = $this->MoveImg($file, true);
        }
        else
        {
            if($this->game->name != $this->_p['name'])
            {
                $sourceImg = $this->RenameImg($file);
            }
            if($this->game->genre_id != $this->_p['genre_id'])
            {
                $sourceImg = $this->MoveImg($file);
            }
            if($this->game->name == $this->_p['name'] && $this->game->genre_id == $this->_p['genre_id'])
            {
                $sourceImg = $file;
            }
        }
        return $sourceImg;
    }

    private function RenameImg($file)
    {
        $fullPath = $this->PrepareImg($file);
        rename($this->rootDir . "storage".$file, $this->rootDir . "storage".$fullPath);
        return $fullPath;
    }
    private function MoveImg($file, $param = false)
    {
        if(empty($file))
        {
            unlink($this->rootDir . "storage".$this->sourceImgType);
        }
        else
        {
            $file = (!$param) ? "storage".$file : $file;
            $fullPath = $this->PrepareImg($file);
            copy( $this->rootDir . $file, $this->rootDir .  "storage".$fullPath);
            unlink($this->rootDir . $file);
            return $fullPath;
        }

    }

    private function PrepareImg($file)
    {
        if(!empty($file))
        {
            $searchCharName = array(" ", "’", "-", "'", ":", "&");
            $searchCharFile = array(" ", "’");
            $pathGenre = str_replace($searchCharFile, "", mb_strtolower($this->GetGenre()));
            $fileName = str_replace($searchCharName, "_", mb_strtolower($this->_p['name']));
            $arrFileName = explode("_", $fileName);
            $name = "";
            foreach($arrFileName as $r)
            {
                if($r != "")
                {
                    $name .= trim($r."_");
                }
            }
            $fileName = preg_replace('%[^A-Za-zА-Яа-я0-9_]%', '', substr($name, 0, -1));
            $extArr = explode(".", $file);
            $typeImg = ($extArr[1] == "png") ? "_s" : "_b";
            $fullPath = "/source_img_base_game/".$pathGenre. "/".$fileName. $typeImg .  ".". $extArr[1];
            return $fullPath;
        }
    }

    private function GetGenre()
    {
        $stmt = $this->conn->dbh->prepare("SELECT name FROM genre WHERE id = :id");
        $stmt->bindParam(":id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->execute();
        $arr = $stmt->fetch(PDO::FETCH_OBJ);
        return $arr->name;
    }
    /* Конец работа со справочниками по играм */



    public function GetMainPageGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games WHERE id_game = ".$id)->fetch(PDO::FETCH_OBJ);
    }
    public function EditMainPageGame($objGame, $id = null)
    {
        $this->UpdateMainPageGameRubric();
        $this->UploadMainPageRubricImg();
        $this->UploadMainPageGameScreenshot();
        $video = $this->CheckVideoPoster();
        $dateReleaseWorld = strtotime($this->_p['date_release_world']);
        $dateReleaseRussia = strtotime($this->_p['date_release_russia']);
        $query = $this->conn->dbh->prepare("UPDATE main_page_games SET id_game = :id_game, date = UNIX_TIMESTAMP(NOW()), game_mode = :game_mode, text = :text, title = :title,
        description = :description,
        keywords = :keywords,
        date_release_world = :date_release_world,
        date_release_russia = :date_release_russia,
        publisher = :publisher,
        publisher_link = :publisher_link,
        publisher_russia = :publisher_russia,
        publisher_russia_link = :publisher_russia_link,
        developer = :developer,
        developer_link = :developer_link,
        official_site = :official_site,
        official_site_link = :official_site_link,
        game_engine = :game_engine,
        distribution = :distribution,
        video_img = :video_img,
        video_link = :video_link,
        sr_os = :sr_os, sr_cpu = :sr_cpu, sr_ram = :sr_ram, sr_video = :sr_video, sr_hdd = :sr_hdd WHERE id_game = :id_game OR id = :id");
        $query->bindParam(":id_game", $this->_p['id-game'], PDO::PARAM_INT);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->bindParam(":game_mode", $this->_p['game_mode'], PDO::PARAM_STR);
        $query->bindParam(":text", $this->_p['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $this->_p['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $this->_p['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $this->_p['keywords'], PDO::PARAM_STR);
        $query->bindParam(":date_release_world", $dateReleaseWorld, PDO::PARAM_INT);
        $query->bindParam(":date_release_russia", $dateReleaseRussia, PDO::PARAM_INT);
        $query->bindParam(":publisher", $this->_p['publisher'], PDO::PARAM_STR);
        $query->bindParam(":publisher_link", $this->_p['publisher_link'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia", $this->_p['publisher_russia'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia_link", $this->_p['publisher_russia_link'], PDO::PARAM_STR);
        $query->bindParam(":developer", $this->_p['developer'], PDO::PARAM_STR);
        $query->bindParam(":developer_link", $this->_p['developer_link'], PDO::PARAM_STR);
        $query->bindParam(":official_site", $this->_p['official_site'], PDO::PARAM_STR);
        $query->bindParam(":official_site_link", $this->_p['official_site_link'], PDO::PARAM_STR);
        $query->bindParam(":game_engine", $this->_p['game_engine'], PDO::PARAM_STR);
        $query->bindParam(":distribution", $this->_p['distribution'], PDO::PARAM_STR);
        $query->bindParam(":video_img", $video[1], PDO::PARAM_STR);
        $query->bindParam(":video_link", $video[0], PDO::PARAM_STR);
        $query->bindParam(":sr_os", $this->_p['sr_os'], PDO::PARAM_STR);
        $query->bindParam(":sr_cpu", $this->_p['sr_cpu'], PDO::PARAM_STR);
        $query->bindParam(":sr_ram", $this->_p['sr_ram'], PDO::PARAM_STR);
        $query->bindParam(":sr_video", $this->_p['sr_video'], PDO::PARAM_STR);
        $query->bindParam(":sr_hdd", $this->_p['sr_hdd'], PDO::PARAM_STR);
        $query->execute();
    }

    public function LastMainPageGame()
    {
        $this->conn->dbh->exec("INSERT INTO main_page_games () VALUES()");
        return $this->conn->dbh->lastInsertId();
    }

    public function GetGameRubrics($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games_rubric WHERE id_main_page_game=".$id." ORDER BY rubric")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetRubricArticles($id)
    {
        return $this->conn->dbh->query("SELECT mpgr_articles.id, mpgr_articles.date, mpgr_articles.header, mpg_rubric.rubric, games.name FROM main_page_games_rubric_articles mpgr_articles
                                                LEFT JOIN main_page_games_rubric mpg_rubric ON mpg_rubric.id = mpgr_articles.id_mpg_rubric
                                                LEFT JOIN games ON games.id = mpg_rubric.id_main_page_game
                                                WHERE id_mpg_rubric=".$id." ORDER BY header")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function  GetGameRubricArticleInfo($id)
    {
        return $this->conn->dbh->query("SELECT mpgr_articles.*, mpg_rubric.id_main_page_game AS id_game
                                            FROM main_page_games_rubric_articles mpgr_articles
                                            LEFT JOIN main_page_games_rubric mpg_rubric ON mpg_rubric.id=mpgr_articles.id_mpg_rubric
                                            WHERE mpgr_articles.id=".$id)->fetch(PDO::FETCH_ASSOC);
    }

    public function  GetGameRubricInfo($id)
    {
        return $this->conn->dbh->query("SELECT mpg_rubric.rubric, g.name as game, g.id, mpg_rubric.id as id_rubric FROM main_page_games_rubric mpg_rubric
                                            LEFT JOIN games g ON g.id=mpg_rubric.id_main_page_game
                                            WHERE mpg_rubric.id=".$id)->fetch(PDO::FETCH_ASSOC);
    }

    public function DeleteRubricArticle($id)
    {
        $video = $this->conn->dbh->query("SELECT video_link, video_img FROM main_page_games_rubric_articles WHERE id=".$id)->fetch(PDO::FETCH_ASSOC);
        if( is_file($this->rootDir.substr($video['video_link'], 1)) ){
            unlink($this->rootDir.substr($video['video_link'], 1));
        }
        if( is_file($this->rootDir.substr($video['video_img'], 1)) ){
            unlink($this->rootDir.substr($video['video_img'], 1));
        }
        $this->conn->dbh->query("DELETE FROM main_page_games_rubric_articles WHERE id=".$id);
    }

    public function AddRubricArticle($params)
    {

        $video = $this->CheckVideoPoster();
        $query = $this->conn->dbh->prepare("
            INSERT INTO main_page_games_rubric_articles
            SET
                date = :date,
                header = :header,
                text = :text,
                title = :title,
                description = :description,
                keywords = :keywords,
                video_link = :video_link,
                video_img = :video_img,
                id_mpg_rubric=:rubric
                ");
        $parts = explode('.', $params['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $video[0], PDO::PARAM_STR);
        $query->bindParam(":video_img", $video[1], PDO::PARAM_STR);
        $query->bindParam(":rubric", $params['id_rubric'], PDO::PARAM_INT);
        $query->execute();

    }

    public function UploadMainPageGameScreenshot()
    {
        $i=0;
        $screenName="screen-file-".$i;
        while($i < 7){
            if(isset($this->_p[$screenName])){
                $imgS = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p[$screenName]);
                $imgB = str_replace("_s", "_b", $imgS);
                $oldImgS = $this->_p[$screenName];
                $oldImgB = str_replace("_s", "_b", $oldImgS);
                if(rename($this->rootDir.$oldImgS, $this->rootDir.$imgS) && rename($this->rootDir.$oldImgB, $this->rootDir.$imgB)){
                    $imgS = "/".$imgS;
                    $imgB = "/".$imgB;
                    $sql = $this->conn->dbh->prepare("INSERT INTO main_page_games_screenshot SET id_main_page_game=:id_game, screenshot_s=:imgS, screenshot_b=:imgB");
                    $sql->bindParam(":id_game", $this->_p['id-game'], PDO::PARAM_INT);
                    $sql->bindParam(":imgS", $imgS, PDO::PARAM_STR);
                    $sql->bindParam(":imgB", $imgB, PDO::PARAM_STR);
                    $sql->execute();
                }
            }
            $i++;
            $screenName="screen-file-".$i;
        }
    }

    public function UpdateMainPageGameRubric()
    {
        if(isset($this->_p['new-rubrics'])){
            $idGame=$this->_p['id-game'];
            $newImgCount = 0;
            foreach($this->_p['new-rubrics'] as $rubric){
                if($rubric==="")
                    continue;
                $imgName = "add-img-files-".$newImgCount;
                if(isset($this->_p[$imgName])){
                    $img = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p[$imgName]);
                    $oldImg = $this->_p[$imgName];
                    if( rename($this->rootDir.$oldImg, $this->rootDir.$img) ){
                        $img = "/".$img;
                    }
                }else{
                    $img = null;
                }
                $sql = $this->conn->dbh->prepare("INSERT INTO main_page_games_rubric SET id_main_page_game=:id_game, rubric=:rubric, img=:img");
                $sql->bindParam(":id_game", $idGame, PDO::PARAM_INT);
                $sql->bindParam(":rubric", $rubric, PDO::PARAM_STR);
                $sql->bindParam(":img", $img, PDO::PARAM_STR);
                $sql->execute();
                $newImgCount++;
            }
        }

        if(isset($this->_p['deleted-rubrics'])){
            $i = 0;
            $rubricIdString = '';
            foreach($this->_p['deleted-rubrics'] as $rubricId){
                $rubricIdString .= ($i==0) ? $rubricId  : ",".$rubricId ;
                $i++;
            }
            $path = $this->conn->dbh->prepare("SELECT img  FROM main_page_games_rubric WHERE FIND_IN_SET(id, :id)");
            $path->bindParam(":id", $rubricIdString, PDO::PARAM_STR);
            $path->execute();
            $pathDelImg = $path->fetchAll(PDO::FETCH_ASSOC);
            foreach($pathDelImg as $pathImg){
                unlink($this->rootDir.substr($pathImg['img'], 1));
            }
            $sql = $this->conn->dbh->prepare("DELETE FROM main_page_games_rubric WHERE FIND_IN_SET(id, :id)");
            $sql->bindParam(":id", $rubricIdString, PDO::PARAM_STR);
            $sql->execute();
        }

        if(is_array($this->_p['rubrics']) && is_array($this->_p['id-rubrics'])){
            $i=0;
            foreach($this->_p['rubrics'] as $rubricName){
                if(isset($this->_p['id-rubrics'][$i])){
                    $upd = $this->conn->dbh->prepare("UPDATE main_page_games_rubric SET rubric=:rubricName WHERE id=:id");
                    $upd->bindParam(":id", $this->_p['id-rubrics'][$i], PDO::PARAM_INT);
                    $upd->bindParam(":rubricName", $rubricName, PDO::PARAM_STR);
                    $upd->execute();
                }
                $i++;
            }
        }
    }

    public function EditGameRubricArticle($params)
    {
        $video = $this->CheckVideoPoster();
        $query = $this->conn->dbh->prepare("
            UPDATE main_page_games_rubric_articles
            SET
                date = :date,
                header = :header,
                text = :text,
                title = :title,
                description = :description,
                keywords = :keywords,
                video_link = :video_link,
                video_img = :video_img
            WHERE id=:id
                ");
        $parts = explode('.', $params['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $video[0], PDO::PARAM_STR);
        $query->bindParam(":video_img", $video[1], PDO::PARAM_STR);
        $query->execute();

    }

    public function UploadMainPageRubricImg()
    {
        if( isset($this->_p['deletedImg']) && is_array($this->_p['deletedImg']) ){
            $i=0;
            $delId ='';
            foreach($this->_p['deletedImg'] as $delImgId){
                $delImgArray = explode('$', $delImgId);
                $delImg = substr($delImgArray[1], 1);
                unlink($this->rootDir.$delImg);
                $delId .= ($i===0) ? $delImgArray[0] : ','.$delImgArray[0];
                $i++;
            }
            if($delImgArray[2]==="rubric"){
                $sql = $this->conn->dbh->prepare("UPDATE main_page_games_rubric SET img=null WHERE FIND_IN_SET(id, :id)");
                $sql->bindParam(":id", $delId, PDO::PARAM_STR);
                $sql->execute();
            }
            if($delImgArray[2]==="screen"){
                $sql = $this->conn->dbh->prepare("DELETE FROM main_page_games_screenshot WHERE FIND_IN_SET(id, :id)");
                $sql->bindParam(":id", $delId, PDO::PARAM_STR);
                $sql->execute();
            }
        }

        if(is_array($this->_p['id-rubrics'])){
            foreach($this->_p['id-rubrics'] as $id){
                $imgName = "img-files-".$id;
                if(!empty($this->_p[$imgName])){
                    $img = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p[$imgName]);
                    $oldImgS = $this->_p[$imgName];
                    if( rename($this->rootDir.$oldImgS, $this->rootDir.$img) ){
                        $img = "/".$img;
                        $sql = $this->conn->dbh->prepare("UPDATE main_page_games_rubric SET img=:img WHERE id=:id");
                        $sql->bindParam(":id", $id, PDO::PARAM_INT);
                        $sql->bindParam(":img", $img, PDO::PARAM_STR);
                        $sql->execute();
                    }
                }
            }
        }
    }

    public function CheckVideoPoster()
    {
        $videoLink = ( !empty($this->_p['video-link']) ) ? $this->_p['video-link'] : null;
        $videoImg = ( !empty($this->_p['video-img']) ) ? $this->_p['video-img'] : null;
        if( !empty($this->_p['video-link']) && strpos($this->_p['video-link'], "temp") ){
            $videoLink = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p['video-link']);
            if(is_file($this->rootDir.$this->_p['video-link']))
                rename($this->rootDir.$this->_p['video-link'], $this->rootDir.$videoLink);
            $videoLink = "/".$videoLink;
        }
        if( !empty($this->_p['img-poster']) && strpos($this->_p['img-poster'], "temp") ){
            $videoImg = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p['img-poster']);
            if(is_file($this->rootDir.$this->_p['img-poster']))
                rename($this->rootDir.$this->_p['img-poster'], $this->rootDir.$videoImg);
            $videoImg = "/".$videoImg;
        }
        if(!empty($this->_p['deleted-video-link'])){
            if(is_file($this->rootDir.substr($this->_p['deleted-video-img'], 1)))
                unlink( $this->rootDir.substr($this->_p['deleted-video-img'], 1) );
            if(is_file($this->rootDir.substr($this->_p['deleted-video-link'], 1)))
                unlink( $this->rootDir.substr($this->_p['deleted-video-link'], 1) );
        }
        return array($videoLink, $videoImg);
    }

    public function GetMainPageScreenshot($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games_screenshot WHERE id_main_page_game=".$id)->fetchAll(PDO::FETCH_ASSOC);
    }
}