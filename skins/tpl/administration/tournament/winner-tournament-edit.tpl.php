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
<form action="<?= Url::Action("save-winner", "administration.tournament") ?>" method="POST" id="edit-winner-tournaments">
    <?=Render::Hidden($_GET['id'], "id_tournament")?>
    <? if(isset($_GET['edit'])){?>
    <?=Render::Hidden($_GET['edit'], "id_row")?>
    <?}?>
    <p>Пользователи</p>
    <select id="select" name="id_winner">
        <? foreach($data['list-users'] as $res){
            if(!isset($_GET['edit'])){
                if($res->winner == $res->id)continue;
            }else{
                $selected = ($data['winner']->winner == $res->id) ? "selected" : "";
            }echo "<option value='".$res->id."' ".$selected.">".$res->nick."</option>";
        }?>
    </select>


    <div class="field">
        <?=Render::LabelTextArea($data['winner']->text, "text", "")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?= Render::LabelEdit($data['winner']->place, "place", "Место")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['winner']->title, "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['winner']->description, "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['winner']->keywords, "keywords", "Ключевые слова")?>
    </div>
    <?php if( !empty($data['winner']->video_link)) { ?>
        <div id="video-upload-btn" class="container upload" style = "display: none;margin-top: 20px">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>
        <div class="span8 demo-video" style="position: relative; top: 22px;">
            <video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster="" data-setup="{}">
                <source src="/storage/winner/tournament-winner-video/<?=$data['winner']->video_link?>" type='video/mp4' />
            </video>
            <input type="hidden" name="video-link" value="<?=$data['winner']->video_link?>">
            <div style="height: 50px; width: 100%">
                <input type="button" value="Удалить видео" id="delete-video">
            </div>
        </div><div style="clear: both;"></div><br>
    <?php } else{ ?>
        <div id="video-upload-btn" class="container upload" style = "margin-top: 20px">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>
    <?php } ?>
    <?php if( !empty($data['winner']->audio_link)) { ?>
        <div id="audio-upload-btn" class="container upload"   style = "display: none;margin-top: 40px">
            <span class="btn">Аудиофайл</span>
            <input id="audio-file" type="file" name="audio-file" />
        </div>
        <div class="demo-audio" style="">
            <audio controls preload="none" data-setup="{}">
                <source src="/storage/winner/tournament-winner-audio/<?=$data['winner']->audio_link?>" type="audio/mpeg" />
            </audio>
            <input type="hidden" name="audio-link" value="<?=$data['winner']->audio_link?>">
            <div style="height: 50px; width: 100%">
                <input type="button" value="Удалить аудио" id="delete-audio">
            </div>
        </div><div style="clear: both;"></div><br>
    <?php } else{ ?>
        <div id="audio-upload-btn" class="container upload"style = "margin-top: 40px">
            <span class="btn">Аудиофайл</span>
            <input id="audio-file" type="file" name="audio-file" />
        </div>
    <?php } ?>

    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#delete-video').click(function(){
            var removeVideoLink = $('input[name=video-link]').val();
            if(removeVideoLink !== undefined ){
                $('form').append("<input type='hidden' name='deleted-video-link' value='"+removeVideoLink+"'>");
            }
            $(this).closest(".demo-video")[0].remove();
            $("#video-file").parent().show();
        });
        $('#delete-audio').click(function(){
            var removeAudioLink = $('input[name=audio-link]').val();
            if(removeAudioLink !== undefined ){
                $('form').append("<input type='hidden' name='deleted-audio-link' value='"+removeAudioLink+"'>");
            }
            $(this).closest(".demo-audio")[0].remove();
            $("#audio-file").parent().show();
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
    var config = {
        form: "edit-winner-tournaments",
        visualProgress: "modal",
        method: "MainPageGame",
        multi: false,
        limit: 1,
        uploadUrl: document.location.href
    };
    function init(){
        initMultiUploader(config);
    }
    $(document).ready(function(){
        initMultiUploader(config);
        $("input[type='file']").on('change',function(){
            $("input[name='audio-file']").replaceWith('<input id="audio-file" type="file" onclick="init()" name="audio-file"/>');
            $("input[name='video-file']").replaceWith('<input id="video-file" type="file" onclick="init()" name="video-file"/>')
        })
    })



</script>