<style>
    .content-tournament {margin-left: 250px; position: relative; bottom: 15px}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-tournament.block.tpl.php'; ?>

<div class="content-tournament">
    <table>
        <tr>
            <td>
                <h2 class="left" style="color: #1abc9c;">Список победителей прошедших турниров</h2>
            </td>

        </tr>
    </table>
    <?
    foreach($data as $r)
    {
        $linkTournament = $this->LinkTournament($r->game, $r->id);
        echo "<div style='padding:15px 0;'>
            <b style='color: #1abc9c; font-size:16px;'>".$r->header."</b><br>
                <img style='padding-right:25px; width:64px;' class='left' src='/storage".$r->game_img."' alt='$r->game' />
                <div class='left' style='width:230px;'>
                <b style='color: #34495e; font-size:14px;'>Завершен: ".date("d.m.Y", $r->end_date)."</b><br>
                <b style='color: #34495e; font-size:14px;'>Победитель: ".$r->nick."</b><br>
                <b style='color: #34495e; font-size:14px;'>Выйгрыш: <span style='color: #1abc9c'>".$r->pay." рублей<span></b></div>
                <div class='left' style='width:390px;'><span style='color: #34495e'>".$r->description."</span>
                <a class='right' style='color: #1abc9c' href='/tournament/winner/".$r->id."'>Читать далее</a>
                </div></div><br class='clear'><div class='br-points'></div>";
    }
    ?>
</div>
