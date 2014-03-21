<?php
namespace classes;
class Sms
{
    const login = "vayas";
    const pass = "3febfa873670be9a77a0c1bfbe1350b5";
    const post = 1;
    const https = 1;
    const charset = "utf-8";
    const from = "support@gs11.ru";
    const debug = 1;

    public function SendSmsWorld($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = "GS11", $query = "")
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1");
        $m = $this->SmsSendCmd("send","cost=3&phones=".urlencode($phones)."&mes=".urlencode($message)."&translit=$translit&id=$id".
            ($format > 0 ? "&".$formats[$format] : "").
            ($sender === false ? "" : "&sender=".urlencode($sender)).
            ($time ? "&time=".urlencode($time) : "").($query ? "&$query" : ""));

        if (self::debug) {
            if ($m[1] > 0)
                echo "Сообщение отправлено успешно. ID: $m[0], всего SMS: $m[1], стоимость: $m[2], баланс: $m[3].\n";
            else
                echo "Ошибка №", -$m[1], $m[0] ? ", ID: ".$m[0] : "", "\n";
        }
        return $m;
    }


    public function GetSmsCost($phones, $message, $translit = 0, $format = 0, $sender = false, $query = "")
    {
        static $formats = array(1 => "flash=1", "push=1", "hlr=1", "bin=1", "bin=2", "ping=1");
        $m = $this->SmsSendCmd("send", "cost=1&phones=".urlencode($phones)."&mes=".urlencode($message).
            ($sender === false ? "" : "&sender=".urlencode($sender)).
            "&translit=$translit".($format > 0 ? "&".$formats[$format] : "").($query ? "&$query" : ""));

        // (cost, cnt) или (0, -error)

        if (self::debug) {
            if ($m[1] > 0)
                echo "Стоимость рассылки: $m[0]. Всего SMS: $m[1]\n";
            else
                echo "Ошибка №", -$m[1], "\n";
        }

        return $m;
    }

    private function SmsSendCmd($cmd, $arg = "")
    {
        $url = (self::https ? "https" : "http")."://smsc.ru/sys/$cmd.php?login=".urlencode(self::login)."&psw=".urlencode(self::pass)."&fmt=1&charset=".self::charset."&".$arg;
        $i = 0;
        do {
            if ($i)
                sleep(2);
            $ret = $this->SmsReadUrl($url);
        }
        while ($ret == "" && ++$i < 3);
        if ($ret == "")
        {
            if (self::debug)
                echo "Ошибка чтения адреса: $url\n";
            $ret = ","; // фиктивный ответ
        }
        return explode(",", $ret);
    }

    private function SmsReadUrl($url)
    {
        $ret = "";
        $post = self::post || strlen($url) > 2000;
        if (function_exists("curl_init"))
        {
            static $c = 0;
            if (!$c) {
                $c = curl_init();
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($c, CURLOPT_TIMEOUT, 60);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            }

            if ($post) {
                list($url, $post) = explode('?', $url, 2);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($c, CURLOPT_URL, $url);
            $ret = curl_exec($c);
        }
        elseif (!self::https && function_exists("fsockopen"))
        {
            $m = parse_url($url);
            $fp = fsockopen($m["host"], 80, $errno, $errstr, 10);

            if ($fp) {
                fwrite($fp, ($post ? "POST $m[path]" : "GET $m[path]?$m[query]")." HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP".($post ? "\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($m['query']) : "")."\r\nConnection: Close\r\n\r\n".($post ? $m['query'] : ""));
                while (!feof($fp))
                    $ret .= fgets($fp, 1024);
                list(, $ret) = explode("\r\n\r\n", $ret, 2);
                fclose($fp);
            }
        }
        else
            $ret = file_get_contents($url);
        return $ret;
    }

    public function SendSmsRussia($phone, $msg)
    {
        $url = "http://gate.smsaero.ru/send/?user=backahell@rambler.ru&password=".self::pass."&to=". $phone ."&text=".urlencode($msg)."&from=GS11";
        if( $curl = curl_init() )
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
            $out = curl_exec($curl);
            curl_close($curl);
            return;
        }
    }

    public function GetTypePhone($phone)
    {
        if($phone{0} == "+")
        {
            if(($phone{1} == 7 || $phone{1} == 8) && $phone{2} == 9)
            {
                $arrPhone['phone'] = "7".substr($phone, -10);
                $arrPhone['type'] = "russia";
            }
            else
            {
                $arrPhone['phone'] = substr($phone, 1);
                $arrPhone['type'] = "world";
            }

        }
        else
        {
            if(($phone{0} == 7 || $phone{0} == 8) && $phone{1} == 9)
            {
                $arrPhone['phone'] = "7".substr($phone, -10);
                $arrPhone['type'] = "russia";
            }
            else
            {
                $arrPhone['phone'] = $phone;
                $arrPhone['type'] = "world";
            }
        }
        return $arrPhone;
    }
}

?>