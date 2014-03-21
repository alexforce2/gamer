<?php
namespace classes;
use classes\SimpleImage;

class Upload extends SimpleImage
{
    private $fileName, $path ,$rootDir;
    public $conn;

    public function __construct($path)
    {
        $this->path = $path;
        $this->rootDir = $_SERVER["DOCUMENT_ROOT"];
    }

    public function UploadImgTinyMce()
    {
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $this->path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            if($_POST['type-file'] == "img")
                $this->load($value['tmp_name'])->save($fileName);
            else
                $this->UploadFile($value['tmp_name'], $fileName);
        }
        echo $fileName;
    }

    public function MainPageGame()
    {
        foreach ($_FILES as $key => $value)
        {
            $this->fileName = $key;
            $this->path = "storage/temp";
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $this->path . "/" . md5(microtime() + rand(0, 10000));
            if($ext === ".mp4"){
                $videoFileName = $name.$ext;
                move_uploaded_file($value['tmp_name'], $videoFileName);
                echo json_encode(array($videoFileName, $this->fileName));
                return;
            }elseif($ext === ".mp3"){
                $audioFileName = $name.$ext;
                move_uploaded_file($value['tmp_name'], $audioFileName);
                echo json_encode(array($audioFileName, $this->fileName));
                return;
            }
            elseif($ext === ".jpeg" || $ext === ".jpg" || $ext === ".png"){
                $fileNameSmall = $name . "_s" . $ext;
                $fileNameBig = $name . "_b" . $ext;
                $this->load($value['tmp_name'])->square_crop(170)->save($fileNameSmall);
                $this->load($value['tmp_name'])->save($fileNameBig);
            }else{
                return;
            }

        }
        echo json_encode(array($fileNameSmall, $this->fileName));
    }

    public function OriginalImg()
    {
        foreach ($_FILES as $key => $value)
        {
            $this->fileName = $key;
            $this->path = "storage/temp";
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $this->path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name.$ext;
            move_uploaded_file($value['tmp_name'], $fileName);
            echo json_encode(array($fileName, $this->fileName));
        }

    }

    public function UploadImg($path)
    {

        foreach ($_FILES as $key => $value)
        {
            $this->fileName = $key;
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            if($key == "source_img_s")
            {
                $this->ImgIconGame($value, $fileName, $ext);
            }
            else if($key == "source_img_b")
            {
                $this->ImgLicenceGame($value, $fileName, $ext);
            }
            else
            {
                if($_POST['type-file'] == "img")
                    $this->load($value['tmp_name'])->save($fileName);
                else
                    $this->UploadFile($value['tmp_name'], $fileName);
            }
        }
        echo json_encode(array($fileName, $this->fileName));
    }

    public function ImgNews()
    {
        foreach ($_FILES as $key => $value)
        {
            $this->fileName = $key;
            $this->path = "storage/temp";
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $this->path . "/" . md5(microtime() + rand(0, 10000));
            if($ext === ".jpeg" || $ext === ".jpg" || $ext === ".png")
            {
                $file = $name . $ext;
                $this->load($value['tmp_name'])->square_crop(170)->save($file);
            }
            else
            {
                return;
            }

        }
        echo json_encode(array($file, $this->fileName));
    }

    // todo: нужно написать метод для валидации входящих файлов

    // Метод загрузки изображений в временную папку на сервере и создание обрезанного изображения
    public function UploadUserGameImg($path)
    {
        foreach($_FILES as $k=>$v)
        {
            $ext = "." . pathinfo($v["name"], PATHINFO_EXTENSION);
            $name = $path . "/" . md5(microtime() + rand(1, 10000));
            $fileName = $name ."_b" . $ext;
            $smallFileName = $name . "_s" . $ext;
            $this->UploadFile($v['tmp_name'], $fileName);
            $this->load($fileName)->square_crop(200)->save($smallFileName);
        }
        echo json_encode(array($smallFileName, $k, $fileName));
    }

    private function ImgIconGame($value, $fileName, $ext)
    {
        if($ext == ".png")
        {
            $this->load($value['tmp_name'])->square_crop(128)->save($fileName);
        }
        else
        {
            echo json_encode(array("Формат файла только PNG"));
            exit();
        }
    }

    private function ImgLicenceGame($value, $fileName, $ext)
    {
        if($ext == ".jpg")
        {
            $this->load($value['tmp_name'])->square_crop(400)->save($fileName);
        }
        else
        {
            echo json_encode(array("Формат файла только JPG"));
            exit();
        }
    }

    private function UploadFile($tmpName, $fileName)
    {
        return move_uploaded_file($tmpName, $fileName); //относительный путь без слеша в начале.
    }
}
