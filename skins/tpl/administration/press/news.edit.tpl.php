<?php
use classes\render;
use classes\url;
?>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
</style>

<h1><a href="<?= Url::Action("news", "administration.press") ?>">Новости</a> -> <?=$data->id > 0 ? "Редактирование" : "Создание" ?></h1>
<form action="<?= Url::Action("edit-news", "administration.press") ?>" id="edit-news" method="POST">
    <?=Render::Hidden($data->id, "id")?>
    <table>
        <tr>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", strtotime($data->date)), "date", "Дата", true)?>
                </div>
            </td>
            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data->header, "header", "Заголовок", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" style="float: left; padding: 10px">
                    <?
                    if(empty($data->img)){
                    ?>
                    <div id="img-upload-btn" class="container upload">
                        <span class="btn">Изображение</span>
                        <input id="img" type="file" name="img" />
                    </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image">
                                <img src="<?=$data->img;?>">
                                <input type="hidden" name="img" value="<?=$data->img;?>">
                            </div>
                        </div>
                    <?}?>
                </div>
            </td>
            <td>
                <div class="field" id="announce-games">
                    <?=Render::LabelTextArea($data->short, "short", "Анонс", true)?>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="field">
        <?=Render::LabelTextArea($data->text, "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data->title, "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data->description, "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data->keywords, "keywords", "Ключевые слова")?>
    </div>
    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>

<script type="text/javascript">
    var config = {
        form: "edit-news",
        dragArea: "dragAndDropFiles",
        visualProgress: "modal",
        img: true,
        method: "ImgNews",
        uploadUrl: document.location.href
    }

    $(document).ready(function(){
        initMultiUploader(config);
    });

</script>

