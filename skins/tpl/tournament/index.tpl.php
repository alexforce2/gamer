<style>
    .main-info-block-tournament {
        width: 310px;
        height: 146px;
        margin: 10px 0px 10px 35px;
        font-size: 14px;
    }
    .content-tournament {margin-left: 250px; position: relative; bottom: 15px}
    .not-auth-apply-tournament{float:left; background-color: #ec7063; font-size: 10px; padding: 5px 15px; margin-right: 10px; width: 115px; border-radius: 5px; line-height: 1.3em;}
    .not-auth-apply-tournament a{text-decoration: none; color: #fff}
    .fund{background-image: url("/skins/img/interface/icon-tournament.png"); background-repeat: no-repeat; background-position: 0px 5px; padding: 5px 56px 0px 45px; height: 33px;}
    .start-end-reg{background-image: url("/skins/img/interface/icon-tournament.png"); background-repeat: no-repeat; background-position: 0px -24px; padding: 5px 0 0 45px; }
    .start-end-tournament{background-image: url("/skins/img/interface/icon-tournament.png"); background-repeat: no-repeat; background-position: 0px -82px; padding: 5px 0 0 45px; }
    .rules-game{background-image: url("/skins/img/interface/icon-tournament.png"); background-repeat: no-repeat; background-position: 0px -139px; padding: 5px 0 0 45px; height: 25px; color: #34495e }
    .rules-all{background-image: url("/skins/img/interface/icon-tournament.png"); background-repeat: no-repeat; background-position: 0px -167px; padding: 5px 0 0 45px; height: 25px; color: #34495e; }
    .img-game-tournament img{width: 64px; position: relative; top:10px;}
</style>
<script type="text/javascript">
function confirmMemberTournament(){
    $.ajax({
        type: 'POST',
        url: document.location.href,
        dataType: 'html',
        data: {'ajax-query': 'true', 'method': 'ConfirmMemberTournament', 'type-class': 'model'},
        beforeSend: function(){},
        success: function(data){
            location.reload();
        }
    });
}

</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-tournament.block.tpl.php'; ?>

    <div class="content-tournament">
        <table>
            <tr>
                <td>
                    <h2 class="left"><?=$data['tournament']->header;?></h2>
                </td>
                <td>
                    <?php
                    if (($_SESSION['auth'] != 1)) {
                        echo '<div style="float: right">
                                <div class="not-auth-apply-tournament">
                                    <a href="/" >Для того что бы подать заявку, авторизуйтесь.</a>
                                </div>
                                <img style="position: relative; top:4px" src="/skins/img/interface/apply.png" alt="Принимать участие могут только зарегистрированные пользователи" title="Принимать участие могут только зарегистрированные пользователи" />

                              </div>';
                    }
                    elseif($_SESSION['auth'] == 1 && !empty($data['my-tournament']))
                    { ?>
                       <div class="right" style="margin-right: 5px;">
<!--                           <a href="javascript:showModal('box-modal-info-tournament-table')" style="margin-right: 5px; text-decoration: none">-->
<!--                               <img title="Турнирная таблица" src="/skins/img/interface/tournament-table.png" />-->
<!--                           </a>-->
                           <a href="/tournament/<?=$this->LinkTournament($data['tournament']->game, $data['tournament']->id);?>&page=external">
                               <img title="Внутренняя страница" src="/skins/img/interface/tournament-back.png" />
                           </a>
                       </div>
                        <br class="clear">
                    <? }
                    else {
					?>
                        <div style="float: right; margin-right: 10px;" >
							<a href="javascript:showModal('box-modal-info-tournament-table')" style="margin-right: 5px; text-decoration: none">
								   <img title="Турнирная таблица" src="/skins/img/interface/tournament-table.png" />
							   </a>
                            <? if($_GET['id'] == '9'){?>
                                <a style="position: relative; bottom: 12px;" href="javascript:void(0)" class="btn-login">Запись на участие закрыта</a>
                            <? }else{ ?>
                                <a style="position: relative; bottom: 12px;" href="javascript:showModal('box-modal-tournament')" class="btn-login">Принять участие</a>

                            <? } ?>
						</div>
                    <?
					}
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <iframe id="prev-frame" style="z-index:0" width="350" height="240" style="z-index:1" src="<?=$data['tournament']->video_rules;?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
                </td>

                <td style="vertical-align: top">
                    <div class="main-info-block-tournament">
                        <div class="left fund">Призовой фонд </div>
                        <div class="right"><b style="color: green; position: relative; top: 3px;"><?=$data['tournament']->pay;?> р.</b></div>

                        <div class="left start-end-reg">Начало регистрации<br>Завершение регистрации</div>
                        <div class="right">
                            <span style="position: relative; top: 5px;"><?=date("d.m.Y", $data['tournament']->start_date_reg); ?><br><?=date("d.m.Y", $data['tournament']->end_date_reg); ?></span>
                        </div>
                        <div class="clear"></div>
                        <div class="br-points-s"></div>
                        <div class="left start-end-tournament">Начало турнира<br>Завершение турнира</div>
                        <div class="right">
                            <span style="position: relative; top: 5px;"><?=date("d.m.Y", $data['tournament']->start_date); ?><br><?=date("d.m.Y", $data['tournament']->end_date); ?></span>
                        </div>
                        <div class="clear"></div>
                        <div class="left" style="margin-top: 15px; width: 240px;">
                            <a href="javascript:showModal('box-modal-info-tournament-game')"  style="text-decoration: none">
                                <div class="left rules-game">Правила турнира по игре</div>
                            </a>
                            <a href="javascript:showModal('box-modal-info-tournament')" style="text-decoration: none">
                                <div class="left rules-all">Общие правила турниров</div>
                            </a>
                        </div>
                        <div class="right img-game-tournament"><img src="/storage<?=$data['tournament']->game_img; ?>" title="<?=$data['tournament']->game; ?>" alt="<?=$data['tournament']->game; ?>" /></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php include $_SERVER['DOCUMENT_ROOT']. "/skins/tpl/tournament/modal.tpl.php"; ?>
<?php
if($this->user['group'] == 1){
?>

<div class="hide">
    <div class="box-modal" id="box-modal-tournament" style="z-index:9999; width: 500px">
        <div class="header-modal">
            <b>Подтверждение о принятии участия в турнире</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            <img id="captcha-image" src="/skins/img/interface/warning-pay.png" class="left">
            <div style="margin-left: 130px;">
                <span>Вы действительно желаете принять участие в турнире? <b style="color: red">Внимание!</b> профиль <b>группы C</b> имеют лишь один доступ к он-лайн турнирам, после чего <b>группа C</b> анулируется.
                    Более подробную информацию читайте <a href="/about/tariff" style="color: #507fb6; text-decoration: none ">здесь.</a>
</span>
            </div><br>
            <div style="float: right"><a href="javascript:confirmMemberTournament()" class="btn-login">Подтверить участие</a>
                <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
        </div>
    </div>
</div>
<?}else{?>
    <div class="hide">
        <div class="box-modal" id="box-modal-tournament" style="width: 500px">
            <div class="header-modal">
                <b>Недостаточно прав для участия в турнире</b>
                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                    <img src="/skins/img/interface/close-modal.png"></div>
            </div>
            <div style="padding:15px; padding-bottom: 45px;">
                <img id="captcha-image" src="/skins/img/interface/question-pay.png" class="left">
                <div style="margin-left: 130px;">
                <span>Для того, чтобы принять участие в турнире, Вам необходимо иметь профиль <b>группы C</b>.
                    Перейдите в систему биллинга для осуществления данной операции, или нажмите "Отмена".</a>
</span>
                </div><br>
                <div style="float: right"><a href="/billing/tariff" class="btn-login">Получить группу C</a>
                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
            </div>
        </div>
    </div>
<?}?>
<? include $_SERVER['DOCUMENT_ROOT'] . "/skins/tpl/block/answer.block.tpl.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT'] . "/skins/tpl/block/share-soc.block.tpl.php"; ?>