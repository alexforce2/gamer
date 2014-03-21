<?php
namespace classes;
class Captcha
{
    const imgDir = '/skins/img/captcha/';
    public static function Init()
    {
        $number = self::GenerateCode();
        $_SESSION['code-captcha'] = $number;
        self::ImgCode($number);
    }

    private static function GenerateCode()
    {
        $str = "q1w2e3r4t5y6u7i8o9p0a1s2d3f4g5h6j7k8l9z0x1c2v3b4n5m6";
        $str = substr($str, mt_rand(1, strlen($str) - 6), mt_rand(5, 8));
        $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($array_mix);
        return implode("", $array_mix);
    }

    private static function ImgCode($code)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: image/gif");
        $lineNum = 4;
        $img_arr = array("1.gif", "2.gif", "3.gif");

        $fontArr = array(
            '0' => array("font-name" => "Stain.ttf", "size" => "18"),
            '1' => array("font-name" => "EpsilonCTT.ttf", "size" => "21"));

        $n = rand(0, sizeof($fontArr) - 1);
        $img_fn = $img_arr[rand(0, sizeof($img_arr) - 1)];
        $im = imagecreatefromgif($_SERVER['DOCUMENT_ROOT'] . self::imgDir . $img_fn);


        for ($i = 0; $i < $lineNum; $i++)
        {
            $color = imagecolorallocate($im, rand(0, 150), rand(100, 100), rand(0, 150));
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }

        $color = imagecolorallocate($im, rand(0, 200), 0, rand(0, 200));
        imagettftext($im, $fontArr[$n]["size"], rand(-1, 4), rand(30, 35), rand(25, 45), $color, $_SERVER['DOCUMENT_ROOT'] . self::imgDir . $fontArr[$n]["font-name"], $code);
        for ($i = 0; $i < $lineNum; $i++)
        {
            $color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 255));
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }
        imagegif($im);
        ImageDestroy($im);
    }
}