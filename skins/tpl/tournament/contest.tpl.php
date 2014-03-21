<style>
    .table-settings{text-align: center;}
</style>
<div>
    <h2 class="left">Конкурс DIABLO 3</h2>
    <div class="right" style="margin-right: 10px;">
        <a style="text-decoration: none" href="/tournament/<?=$this->LinkTournament($data['tournament']->game, $data['tournament']->id);?>&page=internal">
            <img title="Внешняя страница" src="/skins/img/interface/tournament-back.png" />
        </a>
    </div>
    <br class="clear">


    <span>После того как вы отснимите ролик, вы должны будете его отправить на модерацию, для того что бы мы могли оценить вашу игру.</span>
    <h3 style="color: red">Внимание!!! Видеоролик можно прикреплять лишь один раз.</h3>

    <table class="table-settings" style="width: 500px; ">
        <tr>
            <th>
                Действия участника:
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
                        <div id="main-photo-upload-btn" style="position: relative; top:4px; height: 18px; !important" class="container upload left">
                            <span class="btn" style="width: 180px; line-height: 18px !important;">Прикрепить видео</span>
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
</div>
<br class="clear">
<div class="br-points"></div>

