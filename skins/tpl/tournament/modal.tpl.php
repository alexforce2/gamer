<div class="hide">
    <div class="box-modal" id="box-modal-info-tournament" style="width: 600px">
        <div class="header-modal">
            <b>Общие правила турниров</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            Сумма призового фонда <b style="color: red">НЕ МОЖЕТ БЫТЬ УМЕНЬШЕНА,</b> <b style="color: green">НО МОЖЕТ БЫТЬ УВЕЛИЧЕНА.</b><br />
            1) Нельзя прибегать к сторонним программам для того, чтобы изменить игровой процесс (повышение атрибутов, ресурсов и прочее.);<br>
            2) Нельзя использовать чит-коды и баги самой игры в любых целях (наживы, победы и т.д.);<br />
            3) Строго и точно соблюдать все правила турнира (в противном случае вы будете дисквалифицированы);<br />
            4) За любое мошенничество на турнире или попытку нечестной игры, пользователь будет заблокирован от участия в турнирах;<br />
            5) При монтировании видеоролика он не должен состоять из отдельных фрагментов, а должен быть цельным. Если используется видеосъёмка с помощью видеокамеры, видеоролик должен быть чётким и без искажений;<br />
            6) При прохождении игры, необходимо строго соблюдать правила турнира данной игры, иначе вы будете дисквалифицированны;<br />
            7) Пользователь может предоставить видео только один раз за турнир, повторное закачивание видео засчитываться не будет;<br />
            8) Для восстановления участия в турнирах вы должны будете сообщить об этом администрации сайта;<br />
            9) Победителю турнира <strong>я обязуюсь вручить свои личные деньги,</strong> сумма приза определяется в призовом фонде;<br />
        </div>
    </div>
</div>
<div class="hide">
    <div class="box-modal" id="box-modal-info-tournament-game" style="width: 650px">
        <div class="header-modal">
            <b>Правила игры ДОТА 2</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            <?=$data['tournament']->rules; ?>
        </div>
    </div>
</div>

<div class="hide">
    <div class="box-modal" id="box-modal-invite-opponent-tournament" style="width: 650px">
        <div class="header-modal">
            <b>Поиск участника</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            Пользователю <b><?=$data['my']->nick_opponent?></b> было отпраленно сообщение о том, что вы его ожидаете.
        </div>
    </div>
</div>

<div class="hide">
    <div class="box-modal" id="box-modal-search-member-tournament" style="width: 650px">
        <div class="header-modal">
            <b>Поиск участника</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            Смс сообщение было отпраленно, одному из пользователей сайта о том что вы его приглашаете на турнир.
        </div>
    </div>
</div>

<div class="hide">
    <div class="box-modal" id="box-modal-info-tournament-table" style="width: 960px">
        <div class="header-modal">
            <b>Турнирная таблица DOTA 2</b>
            <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            Представляем вашему вниманию турнирную таблицу по игре DOTA 2<br />
            <h4>Турнирная таблица</h4>
            <table id="table-tournament" cellpadding="0" cellspacing="0">
                <tr id="table-tournament-header">
                    <th style="width: 50px">1 раунд</th>
                    <th style="width: 50px">2 раунд</th>
                    <th style="width: 50px">3 раунд</th>
                    <th style="width: 50px">4 раунд</th>
                    <th style="width: 50px">5 раунд</th>
<!--                    <th style="width: 50px">6 раунд</th>-->
<!--                    <th style="width: 50px">7 раунд</th>-->

                </tr>
                <?php
                foreach($data['table-members'] as $r)
                {
                    $r->first_opponent =($r->first_opponent == "") ? "Анонимный" : $r->first_opponent;
                    $r->second_opponent =($r->second_opponent == "") ? "Безымянный" : $r->second_opponent;
                    $r->second_opponent =($r->id_opponent == "") ? "Ожидает соперника" : $r->second_opponent;
                    echo "<tr>";
                    if($r->stage == 1)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    if($r->stage == 2)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    if($r->stage == 3)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    if($r->stage == 4)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    if($r->stage == 5)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    /*if($r->stage == 6)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }
                    if($r->stage == 7)
                    {
                        if($r->game_over == 1)
                            echo "<td style='text-decoration:line-through'>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";
                        else
                            echo "<td>".$r->first_opponent." <b>vs</b> ".$r->second_opponent."</td>";

                    }
                    else
                    {
                        echo "<td>-</td>";
                    }*/
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>


