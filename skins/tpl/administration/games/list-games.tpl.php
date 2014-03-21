<?php
use classes\render;
use classes\url;
?>
<h1>Список игр</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("add-main-list-game", "administration.games") ?>?action=add">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table" style="width: 100%;">
        <thead>
        <tr>
            <th>Заголовок</th>
            <th style="text-align: right">Действие</th>
        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>

                <td class="text-left"><?=$row->name; ?></td>
                <td style="text-align: right">
                    <a href='<?= Url::Action("add-main-list-game", "administration.games") ?>?action=edit&id=<?= $row->id ?>'>Редактироавть</a>
                </td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>