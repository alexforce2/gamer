<style>
    .content-tournament {margin-left: 250px; position: relative; bottom: 15px}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-tournament.block.tpl.php'; ?>

<div class="content-tournament">
    <table>
        <tr>
            <td>
                <h2 class="left" style="color: #1abc9c;">Список текущих турниров</h2>
            </td>

        </tr>
    </table>
    <?
    foreach($data as $r)
    {
        $linkTournament = $this->LinkTournament($r->game, $r->id);
        echo "<div style='padding:15px 0;'><a style='text-decoration:none' href='/tournament/".$linkTournament."&page=internal'>
                <img style='padding-right:25px; width:128px;' class='left' src='/storage".$r->game_img."' alt='$r->game' />
                <div><b style='color: #1abc9c; font-size:16px;'>".$r->header."</b><br>
                <b style='color: #34495e; font-size:14px;'>Даты проведения: ".date("d.m.Y", $r->start_date_reg)." - ".date("d.m.Y", $r->end_date)."</b><br>
                <b style='color: #34495e; font-size:14px;'>Количество участников: ".$r->count_users."</b><br>
                <b style='color: #34495e; font-size:14px;'>Сумма призвого фонда: <span style='color: #1abc9c'>".$r->pay." рублей<span></b><br>
                <span style='color: #34495e'>".$r->description."</span>
                <a class='right' style='color: #1abc9c' href='/tournament/".$linkTournament."&page=internal'>Читать далее</a>
                </div></div><div class='br-points'></div>";
    }
    ?>
</div>
