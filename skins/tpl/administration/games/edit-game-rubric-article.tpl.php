<?php
use classes\render;
use classes\url;
?>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
</style>

<h2><?php if($data['id']>0){?>
    Редактирование статьи "<?=$data['header']?>"
    <?php }else{?>
     Создание новой статьи рубрик "<?=$data['game-rubric']['rubric']?>" игры "<?=$data['game-rubric']['game']?>"
    <?php } ?>
</h2>
<form action="<?= Url::Action("game-rubric-articles", "administration.games") ?><?=($data['id']>0) ? $data['id_mpg_rubric'] : ''?>" method="POST" id="edit-game-rubric-article">
    <?=Render::Hidden($data["id"], "id")?>
    <?=Render::Hidden($data["id_mpg_rubric"], "id_rubric")?>
    <?=Render::Hidden($data["game-rubric"]["id"], "id-game")?>
    <table>
        <tr>
            <td>
                <div class="field">
                    <?=Render::LabelDatePicker(date("d.m.Y", strtotime($data["date"])), "date", "Дата", true)?>
                </div>
            </td>
            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['header'], "header", "Заголовок", true)?>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="field">
        <?=Render::LabelTextArea($data['text'], "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['title'], "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['description'], "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['keywords'], "keywords", "Ключевые слова")?>
    </div>
    <br>
    <!--Загрузка видеофайла постера-->
    <div id="img-poster-btn" class="container upload" style="display: <?=(empty($data['video_img']))?'block':'none'?>" size="original">
        <span class="btn">Постер видео</span>
        <input id="img-poster" type="file" name="img-poster"/>
    </div>
    <?php if( !empty($data['video_link']) ) { ?>
        <div id="video-upload-btn" class="container upload" style = "display: none;">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>

        <div class="span8 demo-video" style="position: relative; top: 22px;">
            <video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster="<?=($data['video_img']!=false)?$data['video_img']:''?>" data-setup="{}">
                <source src="<?=$data['video_link']?>" type='video/mp4' />
            </video>
            <input type="hidden" name="video-link" value="<?=$data['video_link']?>">
            <input type="hidden" name="video-img" value="<?=($data['video_img']!=false)?$data['video_img']:''?>">
            <div style="height: 50px; width: 100%">
                <input type="button" value="Удалить видео" id="delete-video">
            </div>
        </div><div style="clear: both;"></div><br>
    <?php } else{ ?>
        <div id="video-upload-btn" class="container upload">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>
    <?php } ?>

    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>


<script type="text/javascript">
    var config = {
        form: "edit-game-rubric-article",
        visualProgress: "modal",
        img: true,
        method: "MainPageGame",
        multi: false,
        limit: 1,
        uploadUrl: document.location.href
    };
    $(document).ready(function () {
        initMultiUploader(config);
        $('#delete-video').click(function(){
            $("#img-poster-btn").show();
            //var parentElement =$(this).parent();
            //alert(parentElement);
            var removeVideoLink = $('input[name=video-link]').val();
            var removeVideoImg = $('input[name=video-img]').val();
            if(removeVideoLink !== undefined && removeVideoImg !== undefined){
                $('form').append("<input type='hidden' name='deleted-video-link' value='"+removeVideoLink+"'>");
                $('form').append("<input type='hidden' name='deleted-video-img' value='"+removeVideoImg+"'>");
            }
            $(this).closest(".demo-video")[0].remove();
            $("#video-file").parent().show();
        });

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