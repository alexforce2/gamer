<style>
    .table-settings{text-align: center;}
</style>
<script>
    function searchOpponent(modal){
        showModal(modal);
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'method': 'SearchNoticeOpponent', 'type-class': 'model'}
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
            <img src="/skins/img/not-opponent-tournament.jpg" />
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
        <a style="position: relative; bottom: 12px; text-decoration: none" href="javascript:searchOpponent('box-modal-search-member-tournament')" class="btn-login">Посик участника</a>
    </div>
    <br class="clear">
    <h3>Поздравляем! Вы находитесь на <?= $data['my']->stage; ?> стадии.</h3>
    <?php

    ?>
    <span>В течении определённого времени вам будет назначен соперник, используйте чат для того что бы договориться с ним о поединке.
<!--        Уведомление о назначение вы получите в виде бесплатного СМС сообщения-->
    </span>
</div>
<br class="clear">
<div style="height: 4px; margin-top: 10px; background:url(/skins/img/interface/point.png) "></div>
<div style="margin-top: 10px;">
    <b>Особенности стадии:</b> все играют героем <b><?=$data['settings-tournament']->name; ?></b><br>
    <div style="width: 700px; margin-top: 15px; float: left"><?=$data['settings-tournament']->description; ?></div>
    <img style="width: 250px; float: right" src="<?= $data['settings-tournament']->source_img; ?>"><br>
</div>
<div id="jpId"></div>
<?php include $_SERVER['DOCUMENT_ROOT']. "/skins/tpl/tournament/modal.tpl.php"; ?>