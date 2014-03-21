<style>
    .table-settings{text-align: center;}
</style>

<div>
    <h2 class="left">Турнир DOTA 2</h2>
    <div class="right" style="margin-right: 10px;">
        <a href="javascript:showModal('box-modal-info-tournament-table')" style="margin-right: 5px; text-decoration: none">
            <img title="Турнирная таблица" src="/skins/img/interface/tournament-table.png" />
        </a>
        <a href="/tournament/<?=$this->LinkTournament($data['tournament']->game, $data['tournament']->id);?>&page=internal">
            <img title="Внешняя страница" src="/skins/img/interface/tournament-back.png" />
        </a>
    </div>
    <br class="clear">
    <h3 style="color: red">GameOver.</h3>
    <?php

    ?>
    <span>Ваша игра была закончена поражением, но мы несомненно верим в вас и впереди вас ждет ещё множество славных побед.</span>
</div>
<br class="clear">
<div style="height: 4px; margin-top: 10px; background:url(/skins/img/interface/point.png) "></div>
<div style="margin-top: 10px;">
    <b>Особенности стадии:</b> все играют героем <b><?=$data['settings-tournament']->name; ?></b><br>
    <div style="width: 700px; margin-top: 15px; float: left"><?=$data['settings-tournament']->description; ?></div>
    <img style="width: 250px; float: right" src="<?= $data['settings-tournament']->source_img; ?>"><br>
</div>
<br class="clear">
<div class="br-points"></div>


<div id="jpId"></div>
<?php include $_SERVER['DOCUMENT_ROOT']. "/skins/tpl/tournament/modal.tpl.php"; ?>