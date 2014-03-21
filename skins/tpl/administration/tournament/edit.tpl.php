<?php
use classes\render;
use classes\url;
?>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
</style>

<h1><a href="<?= Url::Action("index", "administration.tournament") ?>">Новый герой</a>
    -> <?=$data["id"] > 0 ? "Редактирование" : "Создание" ?></h1>
<form action="<?= Url::Action("edit", "administration.tournament") ?>" method="POST">
    <?=Render::Hidden($data["id"], "id")?>
    <table>
        <tr>
            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['name'], "name", "Имя", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['description'], "description", "Описание", true)?>
                    <input type='hidden' id='add-images' name='source_img' value='<?=($data['source_img'] != "") ? $data['source_img'] : ""; ?>'>
                </div>
            </td>
        </tr>
    </table>
    <div class="field" style="width: 800px;">
        <div class="container upload">
            <span class="btn" style="width: 220px;">Изображение героя</span>
            <input id="file" type="file" style="width: 220px;" name="file[]"/>
        </div>
        <div id="info-others" style="padding: 10px;"></div>
        <?php
        if($data['source_img'] != "")
            echo "<div class='left'><img style='width:75px; padding-right: 10px;' src='".$data['source_img']."'></div>";
        ?>
    </div>


    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#file').bind('change', function () {
            execUpload(true, 'file', 'info-others');
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
            data.append('name', $("input[name=name]").val());

            if (error != '') {
                $('#'+result).html(error);
            } else {
                $.ajax({
                    url: "/administration/tournament/upload",
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
                        $("#add-images").val(resJson.filename);
                        $("#"+result).append("<div class='left'><img style='width:75px; padding-right: 10px;' src='"+resJson.filename+"'></div>");
                    },
                    error: errorHandler = function () {
                        $(".progress_container").remove();
                        $('#'+result).html('Ошибка загрузки файлов');
                    }
                });

            }

        }
    });
</script>