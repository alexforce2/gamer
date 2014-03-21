<?php
namespace classes;
class Mailer
{
    public $to, $from, $mailFrom, $subject, $message = '';
    public $charset = 'utf-8';
    public $mailerErrors = array('no_text' => 'Нет текста сообщения',
        'no_file' => 'Путь к файлу не указан',
        'no_path' => 'Нет файла по указанному пути',
        'no_open' => 'Не удалось открыть файл',
        'no_address' => 'Не указан адресс',
        'no_sender' => 'Не указан отправитель',
        'no_theme' => 'Не указана тема письма',
        'no_send' => 'По техническим причинам отправка письма в настоящее время невозможна');

    private $boundary1, $boundary2, $plain, $html, $attachImg, $attachment, $multiPart, $header = '';
    private $errors = array();
    private $dummy = 'Ваш почтовый клиент не поддерживает спецификацию MIME 1.0';
    private $mime = array(
        'ai' => 'application/postscript',
        'aif' => 'audio/x-aiff',
        'aifc' => 'audio/x-aiff',
        'aiff' => 'audio/x-aiff',
        'avi' => 'video/x-msvideo',
        'bin' => 'application/macbinary',
        'bmp' => 'image/bmp',
        'cpt' => 'application/mac-compactpro',
        'css' => 'text/css',
        'dcr' => 'application/x-director',
        'dir' => 'application/x-director',
        'doc' => 'application/msword',
        'dvi' => 'application/x-dvi',
        'dxr' => 'application/x-director',
        'eml' => 'message/rfc822',
        'eps' => 'application/postscript',
        'gif' => 'image/gif',
        'gtar' => 'application/x-gtar',
        'htm' => 'text/html',
        'html' => 'text/html',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'hqx' => 'application/mac-binhex40',
        'js' => 'application/x-javascript',
        'log' => 'text/plain',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'mif' => 'application/vnd.mif',
        'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'mpe' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpga' => 'audio/mpeg',
        'oda' => 'application/oda',
        'pdf' => 'application/pdf',
        'php' => 'application/x-httpd-php',
        'php3' => 'application/x-httpd-php',
        'php4' => 'application/x-httpd-php',
        'phps' => 'application/x-httpd-php-source',
        'phtml' => 'application/x-httpd-php',
        'png' => 'image/png',
        'ppt' => 'application/vnd.ms-powerpoint',
        'ps' => 'application/postscript',
        'qt' => 'video/quicktime',
        'ra' => 'audio/x-realaudio',
        'ram' => 'audio/x-pn-realaudio',
        'rm' => 'audio/x-pn-realaudio',
        'rpm' => 'audio/x-pn-realaudio-plugin',
        'rtf' => 'text/rtf',
        'rtx' => 'text/richtext',
        'rv' => 'video/vnd.rn-realvideo',
        'shtml' => 'text/html',
        'sit' => 'application/x-stuffit',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'swf' => 'application/x-shockwave-flash',
        'tar' => 'application/x-tar',
        'text' => 'text/plain',
        'txt' => 'text/plain',
        'tgz' => 'application/x-tar',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
        'wav' => 'audio/x-wav',
        'wbxml' => 'application/vnd.wap.wbxml',
        'wmlc' => 'application/vnd.wap.wmlc',
        'word' => 'application/msword',
        'xht' => 'application/xhtml+xml',
        'xhtml' => 'application/xhtml+xml',
        'xl' => 'application/excel',
        'xls' => 'application/vnd.ms-excel',
        'xml' => 'text/xml',
        'xsl' => 'text/xml',
        'zip' => 'application/zip');

    public function __construct($message = '', $language = false)
    {
        $this->boundary1 = '==' . uniqid(time() + 1);
        $this->boundary2 = '==' . uniqid(time() + 2);

        if (is_array($language))
            $this->mailerErrors = $language;

        if (!empty($message))
        {
            $this->message = $message;
            $this->plain .= "Content-type: text/plain; charset=\"" . $this->charset . "\"\r\n";
            $this->plain .= "Content-Transfer-Encoding: base64\r\n\r\n";
        } else
            $this->errors[] = $this->mailerErrors['no_text'];
    }

    public function CreateTo($to = '')
    {
        (empty($to)) ? $this->errors[] = $this->mailerErrors['no_address'] : $this->to = $to;
    }

    public function CreateFrom($mailFrom = '')
    {
        if (empty($mailFrom))
        {
            $this->errors[] = $this->mailerErrors['no_sender'];
            return;
        }
        else
        {
            $this->from = '=?' . $this->charset . '?b?' . base64_encode($this->from) . '?=';
            $this->mailFrom = ' <' . $mailFrom . '>';
            $this->CreateHeader();
        }
    }

    public function CreateSubject($subject = false)
    {
        ($subject) ? $this->subject = '=?' . $this->charset . '?b?' . base64_encode($subject) . '?=' : $this->errors[] = $this->mailerErrors['no_theme'];
    }

    private function CreateHeader()
    {
        $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $this->header = "Date: " . date('D, d M Y h:i:s O') . "\r\n";
        $this->header .= "From: GS11 <".$this->subject.">\r\n";
        $this->header .= "Message-ID: <" . md5(uniqid(time())) . "@" . $host . ">\r\n";
        $this->header .= "X-Mailer:  (gs11.ru)\r\n";
        $this->header .= "MIME-Version: 1.0\r\n";
    }

    public function SetHtml($set = true)
    {
        if ($set)
        {
            $this->html = "Content-type: text/html; charset=\"" . $this->charset . "\"\r\n";
            $this->html .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $this->html .= chunk_split(base64_encode($this->message)) . "\r\n";
            $this->message = strip_tags($this->message) . "\r\n";
        }
    }

    public function AttacheImg($filename = 'imd')
    {
        $this->attachImg = "Content-ID: <" . $filename . "> \r\n\r\n";
    }

    public function AttacheFile($file = '', $filename = '')
    {
        if (!empty($file))
        {
            if (!empty($this->attachment))
                $this->attachment .= "--" . $this->boundary1 . "\r\n";

            if (is_file($file))
            {
                if (!$f = @fopen($file, 'rb'))
                {
                    $this->errors[] = $this->mailerErrors['no_open'];
                    return;
                }

                $buffer = fread($f, filesize($file));
                fclose($f);

                $filename = (empty($filename)) ? basename($file) : '=?' . $this->charset . '?b?' . base64_encode($filename) . '?=';

                if (function_exists('mime_content_type'))
                    $type = mime_content_type($file);

                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                if (empty($type) && in_array($ext, array_keys($this->mime)))
                    $type = $this->mime[$ext];

                if (empty($type))
                    $type = 'application/octet-stream';

                $this->attachment .= "Content-type: " . $type . '; name="' . $filename . "\"\r\n";
                $this->attachment .= "Content-disposition: attachment; filename=\"" . $filename . "\"\r\n";
                $this->attachment .= "Content-Transfer-Encoding: base64\r\n";
                $this->attachment .= (!empty($this->attachImg) && getimagesize($file)) ? $this->attachImg : "\r\n";
                $this->attachment .= chunk_split(base64_encode($buffer)) . "\r\n\r\n";
            }
            else
                $this->errors[] = $this->mailerErrors['no_file'];

        }
        else
            $this->errors[] = $this->mailerErrors['no_path'];
    }

    private function CreateMultiPart()
    {
        $this->multiPart = $this->dummy . "\r\n\r\n";
        $this->plain .= chunk_split(base64_encode($this->message)) . "\r\n";

        if (empty($this->html) && empty($this->attachment))
        {
            $this->header .= "Content-type: multiPart/mixed; "
                . "boundary=\"" . $this->boundary1 . "\"\r\n";

            $this->multiPart .= "--" . $this->boundary1 . "\r\n";
            $this->multiPart .= $this->plain;
            $this->multiPart .= "--" . $this->boundary1 . "--\r\n";
        }
        else if (empty($this->html))
        {
            $this->header .= "Content-type: multiPart/mixed; "
                . "boundary=\"" . $this->boundary1 . "\"\r\n";

            $this->multiPart .= "--" . $this->boundary1 . "\r\n";
            $this->multiPart .= "Content-type: multiPart/related; "
                . "boundary=\"" . $this->boundary2 . "\"\r\n\r\n";

            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= $this->plain;
            $this->multiPart .= "--" . $this->boundary2 . "--\r\n";
            $this->multiPart .= "--" . $this->boundary1 . "\r\n";
            $this->multiPart .= $this->attachment;
            $this->multiPart .= "--" . $this->boundary1 . "--";
        }
        else if (empty($this->attachment))
        {
            $this->header .= "Content-type: multiPart/alternative; "
                . "boundary=\"" . $this->boundary2 . "\"\r\n";

            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= $this->plain;
            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= $this->html;
            $this->multiPart .= "--" . $this->boundary2 . "--";
        }
        else
        {
            $this->header .= "Content-type: multiPart/mixed; "
                . "boundary=\"" . $this->boundary1 . "\"\r\n";

            $this->multiPart .= "--" . $this->boundary1 . "\r\n";
            $this->multiPart .= "Content-type: multiPart/alternative; "
                . "boundary=\"" . $this->boundary2 . "\"\r\n\r\n";

            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= $this->plain;
            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= $this->html;
            $this->multiPart .= "--" . $this->boundary2 . "\r\n";
            $this->multiPart .= "--" . $this->boundary1 . "\r\n";
            $this->multiPart .= $this->attachment;
            $this->multiPart .= "--" . $this->boundary1 . "--";
        }
    }

    private function CheckData()
    {
        return (count($this->errors)) ? "Mailer error: \n" . implode(PHP_EOL, $this->errors) : false;
    }

    public function SendMail()
    {
        if (!$error = $this->CheckData())
        {
            $this->CreateMultiPart();
            return (!mail($this->to, $this->subject, $this->multiPart, $this->header, '-f' . $this->from)) ? "Mailer error: \n" . $this->mailerErrors['no_send'] : null;
        }
        return $error;
    }
}