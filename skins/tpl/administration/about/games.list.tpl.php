<?php
use classes\render;
use classes\url;
?>
<h1>Игры навсегда</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("add-game", "administration.about") ?>">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table" style="width: 500px">
        <thead>
        <tr>

            <th>Игра</th>

        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>

                <td class="text-left"><?=$row->name_game; ?></td>
                <td><a href='<?= Url::Action("edit-game", "administration.about") ?>?id=<?= $row->id; ?>'>Редактировать</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete-game", "administration.about") ?>?id=<?= $row->id; ?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>