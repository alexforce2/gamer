<?php

namespace classes;
use classes\url as Url;


class Render
{

    static function Label($title)
    {
        return "<label>" . $title . "</label><br>";
    }

    static function RequiredLabel($title, $required)
    {
        return self::Label($title . ($required ? '<span class="required">*</span>' : '') . "");
    }

    static function Hidden($val = "", $name)
    {
        return '<input type="hidden" value="' . $val . '" name="' . $name . '" />';
    }

    static function LabelEdit($val, $name, $title, $required = false, $attributes = array())
    {
        $label = self::RequiredLabel($title, $required);
        $input = self::Edit($val, $name, $required, $attributes);
        return $label . $input;
    }

    static function Edit($val, $name, $required = false, $attributes = array())
    {
        $attributes = array_merge($attributes,
            array("type" => "text", "value" => $val, "name" => $name, "data-required" => $required));
        $attributeStr = self::Attributes($attributes);
        $input = '<input ' . $attributeStr . ' />';
        return $input;
    }

    static function LabelTextArea($val, $name, $title, $required = false, $attributes = array())
    {
        $label = self::RequiredLabel($title, $required);
        $input = self::TextArea($val, $name, $required, $attributes);
        return $label . $input;
    }

    static function TextArea($val, $name, $required = false, $attributes = array())
    {
        $attributes = array_merge($attributes,
            array("id" => $name, "name" => $name, "data-required" => $required));
        $attributeStr = self::Attributes($attributes);
        return '<textarea ' . $attributeStr . ' >' . $val . '</textarea>';
    }

    static function CheckBox($val, $name, $attributes = array())
    {
        $attributes = array_merge($attributes,
            array("id" => $name, "name" => $name, "type" => "checkbox"));
        if ($val)
            $attributes["checked"] = "checked";
        $input = '<input ' . self::Attributes($attributes) . ' />';
        return $input;
    }

    static function LabelDatePicker($val, $name, $title, $required = false, $attributes = array())
    {
        $label = self::RequiredLabel($title, $required);
        $input = self::DatePicker($val, $name, $required, $attributes);
        return $label . $input;
    }

    static function DatePicker($val, $name, $required = false, $attributes = array())
    {
        $attributes["class"] = (!empty($attributes["class"]) ? $attributes["class"] : "") . " datepicker";
        return self::Edit($val, $name, $required, $attributes);
    }

    static function LabelImageUpload($val, $name, $title)
    {
        $label = self::Label($title);
        $control = self::ImageUpload($val, $name);
        return $label . $control;
    }

    static function ImageUpload($val, $name)
    {
        $html =
            "<div class=\"image-upload\" id=\"" . $name . "_control\" data-control-name=\"" . $name . "\"><a id=\"upload_link\">Загрузить изображение</a>" .
                "<span id=\"status\"></span>" .
                "<div id=\"files\"></div>";
        $html .= Render::Hidden(htmlspecialchars(json_encode($val)), $name);
        $html .= Render::Hidden("", $name . "_add");
        $html .= Render::Hidden("", $name . "_delete");
        $html .= "</div>";
        return $html;
    }

    static function ActionLink($title, $action, $controller=null)
    {
        return "<a href='" . Url::Action($action, $controller) . "'>" . $title . "</a>";
    }

    private static function Attributes($attributes)
    {
        $html = "";
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                if (!empty($value)) {
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        return $html;
    }

    static function FormatDate($dateString)
    {
        //date_default_timezone_set();
        if (!$dateString)
            return "";
        return date("d.m.Y", strtotime($dateString));
    }

    static function ToDbDate($russianDateString)
    {
        $event_parts = explode('.', $russianDateString);
        return $event_parts[2] . $event_parts[1] . $event_parts[0];
    }
}