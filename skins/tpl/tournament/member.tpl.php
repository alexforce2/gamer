<style>
    .table-settings{text-align: center;}
</style>
<script>
    function noticeOpponent(modal, opponent){
        showModal(modal);
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'method': 'SendNoticeOpponent', 'type-class': 'model', 'opponent':opponent}
        });
    }

</script>
<div class="left" style="margin-right: 20px;" id="chat_body">
    <div class="header-chat">
        <div class="header-chat-left">
            <img src="/skins/img/interface/chat.png" /><b style="color: #1abc9c">Чат</b>
        </div>
        <div class="left header-chat-middle">
            <img src="<?=$this->user['img_avatar']; ?>"  />
            <span>VS</span>
            <img src="<?=$data['my']->img_avatar_opponent; ?>" />
        </div>
        <div class="header-chat-right">
            <img style="cursor: pointer" src="/skins/img/interface/info-tournament-chat.png" onclick="showModal('box-modal-info-tournament-game')"  title="Правила турнира" />
            <img style="cursor: pointer" src="/skins/img/interface/info-tournament-game-chat.png" onclick="showModal('box-modal-info-tournament')" title="Общие правила" />
        </div>
    </div>
    <div id="scrollbar1">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb">
                    <div class="end"></div>
                </div>
            </div>
        </div>
        <div class="viewport">
            <div class="overview">
                <div id="chat_text_field">
                    <b id="load-chat" style="color: white">Загрузка...</b>
                    <?php echo $message_code; ?>
                </div>
            </div>
        </div>
    </div>
    <input id="last_act" name="last_act" type="hidden" value="<?php echo $last_act; ?>" />
    <input id="block" name="block" type="hidden" value="no" />
    <textarea id="chat_text_input" name="chat_text_input"></textarea>
</div>
<div style="float: right; width: 510px;">
    <h2 class="left">Турнир DOTA 2</h2>
    <div class="right" style="margin-right: 10px;">
        <a style="text-decoration: none" href="javascript:showModal('box-modal-info-tournament-table')" style="margin-right: 5px; text-decoration: none">
            <img title="Турнирная таблица" src="/skins/img/interface/tournament-table.png" />
        </a>
        <a style="text-decoration: none" href="/tournament/<?=$this->LinkTournament($data['tournament']->game, $data['tournament']->id);?>&page=internal">
            <img title="Внешняя страница" src="/skins/img/interface/tournament-back.png" />
        </a>
        <a style="position: relative; bottom: 12px; text-decoration: none" href="javascript:noticeOpponent('box-modal-invite-opponent-tournament', '<?=$data["my"]->id_opponent; ?>')" class="btn-login">Приглашение соперника</a>
    </div>
    <br class="clear">
    <h3>Поздравляем! Вы находитесь на <?= $data['my']->stage; ?> стадии.</h3>
    <?php

    ?>
    <span>После того как вы победили соперника сохраните скриншот и и прикрипите его в качестве доказательства,
        если вы не согласны с победителем и считаете что он жульничал вы можете обратиться к нам в тех поддрежку.</span>
    <div style="margin-top: 10px;">
        <b style="font-size: 16px">Ваш соперник:</b> <b style="font-size: 20px; color: #19bc9c; margin-left: 15px;"><?=$data['my']->nick_opponent?></b>
    </div>
    <div class="left">
        <img style="padding-right:10px; width: 150px; border-radius: 3px;" src="<?=$data['my']->img_avatar_opponent; ?>" />
    </div>
    <div style="border-radius: 3px; border: 1px solid #f6f7f7; width: 180px; height: 150px; float: left; padding: 5px;" >
        <b>Игровой стаж:</b> <?=($data['my']->game_experience_opponent == "") ? "Не указан" : $data['my']->game_experience_opponent; ?><br>
        <b>Побед в турнирах:</b> 0<br>
        <b>Побед в конкурсах:</b> 0<br>
        <b>О себе:</b> <?=$data['my']->about_me_opponent; ?>
    </div>
    <div class="clear"></div>
    <br>
    <table class="table-settings" style="width: 500px; ">
        <tr>
        <th>
            Действия победителя:
        </th>
        </tr>
        <tr>
            <?php if($data['winner']){?>
                <td>
                    <b style="color: green">Ваша информация передана на модерацию ожидайте в течении суток.</b>
                </td>
            <?} else {?>
                <td>
                    <div id="action-winner">
                        <div id="main-photo-upload-btn" style="position: relative; top:4px;" class="container upload left">
                            <span class="btn" style="width: 180px">Прикрепить скриншот</span>
                            <input id="file" type="file" style="width: 180px" name="file[]"/><br>
                        </div>
                        <div class="left" style="margin-left: 190px; width: 200px">
                        <input id="text-winner" type="text" style="width: 180px" placeholder="Добавьте описание"/>
                        </div><br><br><br>
                        <a id="restores" onclick="setWinner()" style="cursor: pointer" class="btn-reg">Победа</a>
                    </div>
                </td>
            <?}?>
        </tr>
        </table>
<!--    <div style="height: 4px; background:url(/skins/img/interface/point.png) "></div>-->
</div>
<br class="clear">
<div class="br-points"></div>
<div style="margin-top: 10px;">
    <b>Особенности стадии:</b> все играют героем <b><?=$data['settings-tournament']->name; ?></b><br>
    <div style="width: 700px; margin-top: 15px; float: left"><?=$data['settings-tournament']->description; ?></div>
    <img style="width: 250px; float: right" src="<?= $data['settings-tournament']->source_img; ?>"><br>
</div>
<br class="clear">
<div class="br-points"></div>

<div id="jpId"></div>
<?php include $_SERVER['DOCUMENT_ROOT']. "/skins/tpl/tournament/modal.tpl.php"; ?>
<script>
    function setWinner(){
        var textWinner = $('#text-winner').val();
        var imgWinner = $('#img-winner').val();
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'method': 'SetWinner', 'type-class': 'model', 'text-winner': textWinner, 'img-winner': imgWinner},
            beforeSend: function(){
                $('#ajax-login-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
            },
            success: function(data){
                if(data == "")
                 location.reload()
                else{
                    $("#error-winner").remove();
                    $("#action-winner").append("<b style='color:red' id='error-winner'><br><br>"+data+"</b>");
                }

            }
        });
    }
    $(document).ready(function () {
        $('#file').bind('change', function () {
            execUpload(false, 'file', 'info');
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
                $('#restores').html(error);
            } else {
                if(param == true)
                    $.ajax({url: document.location.href, type: 'POST', data: {"multi-load":true}})
                $.ajax({
                    url: "/tournament/upload",
                    type: 'POST',
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        $("#main-photo-upload-btn").before('<div class="progress_container"><div class="progress_bar tip"></div></div>');
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
                        if(typeof resJson.error == 'undefined'){
                            if(param != true){
                                $("#restores").append("<input type='hidden' id='img-winner' value='"+data+"'>");
                                $("#main-photo-upload-btn").hide();
                                $("#main-photo-delete-btn").show();
                                $("#restores").after("<div class='left'><img style='width:75px; padding-right: 10px;' src='"+resJson.filename+"'></div>");
                            }
                        }
                        else
                            $("#"+result).html("<b style='color: red'>"+resJson.error+"</b>");
                    },
                    error: errorHandler = function () {
                        $(".progress_container").remove();
                        $('#restores').html('Ошибка загрузки файлов');
                    }
                });

            }
        }

    });
</script>