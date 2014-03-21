<?php
namespace classes;

class OftenFunctions
{
   /*
    *   $num  - число:
    *   $str1 - текст для варианта «один»:
    *   $str2 - текст для варианта «несколько» (2–4)
    *   $str3 - текст для варианта «много»
    */
    public static function getCorrectStr($num, $str1, $str2, $str3) {
        $val = $num % 100;

        if ($val > 10 && $val < 20) {
            $str = $str3;
        }
        else {
            $val = $num % 10;
            if ($val == 1) {
                $str = $str1;
            }
            elseif ($val > 1 && $val < 5) {
                $str = $str2;
            }
            else {
                $str = $str3;
            }
        }
        return $str;
    }

    /*
    *   ФУНКЦИЯ ЗАМЕНА СИМВОЛОВ, ПРИ НАБОРЕ РУССКИХ СЛОВ НА АНГЛ. ЯЗЫКЕ
    */
    public static function getCorrectText($string) {

        $rus_alphabet = array(
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
            'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
            'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        );

        $rus_alphabet_translit = array(
            'F', '<', 'D', 'U', 'L', 'T', '~', ':', 'P', 'B', 'Q',
            'R', 'K', 'V', 'Y', 'J', 'G', 'H', 'C', 'N', 'E', 'A',
            '{', 'W', 'X', 'I', 'O', '}', 'S', 'M', '"', '>', 'Z',
            'f', ',', 'd', 'u', 'l', 't', '`', ';', 'p', 'b', 'q',
            'r', 'k', 'v', 'y', 'j', 'g', 'h', 'c', 'n', 'e', 'a',
            '[', 'w', 'x', 'i', 'o', ']', 's', 'm', '', '.', 'z'
        );

        return str_replace($rus_alphabet_translit, $rus_alphabet, $string);
    }

}