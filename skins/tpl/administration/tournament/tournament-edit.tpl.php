<?php
use classes\render;
use classes\url;
?>
<script type="text/javascript" src="/skins/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/skins/js/tinymce/jquery.tinymce.min.js"></script>
<style>
    .field-long input{width: 940px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
    .table-date-tournament td{width: 240px;}
</style>

<h1><a href="<?= Url::Action("index", "administration.tournament") ?>">Турнир</a>-> <?=$data['tournament']->id > 0 ? "Редактирование" : "Создание" ?></h1>
<form action="<?= Url::Action("save-tournament", "administration.tournament") ?>" method="POST">
    <?=Render::Hidden($data['tournament']->id, "id")?>
    <table class="table-date-tournament">
        <tr>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", $data['tournament']->start_date_reg), "start_date_reg", "Дата регистрации (начало)", true)?>
                </div>
            </td>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", $data['tournament']->end_date_reg), "end_date_reg", "Дата регистрации (завершение)", true)?>
                </div>
            </td>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", $data['tournament']->start_date), "start_date", "Дата турнира (начало)", true)?>
                </div>
            </td>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", $data['tournament']->end_date), "end_date", "Дата турнира (завершение)", true)?>
                </div>
            </td>
        </tr>
    </table>
    <div class="field fill field-long">
        <?=Render::LabelEdit($data['tournament']->header, "header", "Заголовок", true)?>
    </div>
    <div class="field fill field-long" >
        <?=Render::LabelEdit($data['tournament']->video_rules, "video_rules", "Видео-превью", true)?>
    </div>
    <div class="field fill" >
        <?=Render::LabelEdit($data['tournament']->pay, "pay", "Сумма выйгрыша", true)?>
    </div>
    <div class="field fill">
        Турнир по игре<br>
        <select name="id_game">
            <? foreach($data['games'] as $res){
                $selected = ($data['tournament']->id_game == $res->id) ? "selected" : "";
                echo "<option value='".$res->id."' ".$selected.">".$res->name."</option>";
            }?>
        </select>
    </div>


    <div class="field fill">
        Состояние турнира<br>
        <select name="state">
            <? foreach($data['state-tournament'] as $key => $val){
                $selected = ($data['tournament']->state == $key) ? "selected" : "";
                echo "<option value='".$key."' ".$selected.">".$val."</option>";
            }?>
        </select>
    </div>




    <div class="field">
        <?=Render::LabelTextArea($data['tournament']->rules, "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['tournament']->title, "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['tournament']->description, "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['tournament']->keywords, "keywords", "Ключевые слова")?>
    </div>

    <div class="field" style="margin-top: 25px;">
        <div id="main-photo-upload-btn" class="container upload">
            <input type="hidden" name="source_img" value="<?=$data['tournament']->source_img; ?>" />
            <span class="btn">Изображение турнира</span>
            <input id="file" type="file" name="file[]"/>
        </div>
        <div id="main-photo-delete-btn" class="container upload hide">
            <span class="btn" onclick="deleteMainPhoto()">Удалить фото</span>
        </div>
        <div id="info" style="padding: 10px;"></div>
    </div>
    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function () {
        $('#file').bind('change', function () {
            execUpload(false, 'file', 'info');
        })
        $('#file-others').bind('change', function () {
            execUpload(true, 'file-others', 'info-others');
        })
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                var percentComplete = parseInt((e.loaded / e.total) * 100);
                $('.progress_bar').animate({width: percentComplete + "%"}, 10);
            }
        }
        function execUpload(param, id, result){
            var data = new FormData();
            var error = '';
            jQuery.each($('#'+id)[0].files, function (i, file) {
                data.append('file-' + i, file);
            });

            if (error != '') {
                $('#'+result).html(error);
            } else {
                if(param == true)
                    $.ajax({url: "/administration/news/upload", type: 'POST', data: {"multi-load":true}})
                $.ajax({
                    url: "/administration/news/upload",
                    type: 'POST',
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        $("#"+result).before('<div class="progress_container"><div class="progress_bar tip"></div></div>');
                        $(".progress_container").css("margin","10px 0");
                        if (myXhr.upload) {
                            myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                        }
                        return myXhr;

                    },
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (data) {
                        $(".progress_container").remove();
                        var resJson = $.parseJSON(data);
                        if(typeof resJson.error == 'undefined')
                        {
                            if(param != true){
                                add_image_to_editor(resJson.filename);
                                $("form").append("<input type='hidden' id='img-main' name='img-main' value='"+data+"'>");
                                $("#main-photo-upload-btn").hide();
                                $("#main-photo-delete-btn").show();
                            } else {
                                $("form").append("<input type='hidden' id='add-images' name='add-images' value='"+data+"'>");
                                var resHtml = '';
                                for(var i = 0; i < resJson.big.length; i++){
                                    resHtml += "<div class='left'><img style='width:75px; padding-right: 10px;' src='/"+resJson.small[i]+"'></div>";
                                }
                                $("#"+result).append(resHtml);

                            }
                        }
                        else
                            $("#"+result).html("<b style='color: red'>"+resJson.error+"</b>");
                    },
                    error: errorHandler = function () {
                        $(".progress_container").remove();
                        $('#'+result).html('Ошибка загрузки файлов');
                    }
                });

            }

        }
        function add_image_to_editor(image){
            var content = "<img class=\"left\" id=\"main_image\" src=\"" + image + "\">";
            $(tinyMCE.activeEditor.getBody()).prepend(content);

        }
    });
    function deleteMainPhoto(){
        $("#main-photo-upload-btn").show();
        $("#main-photo-delete-btn").hide();
        $("#img-main").remove();
        $(tinyMCE.activeEditor.getBody()).find("#main_image").remove();
    }

</script>