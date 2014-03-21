<?php
use classes\render;
use classes\url;
?>
<h1>Благодарности</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("add-thanks", "administration.about") ?>">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table" style="width: 500px">
        <thead>
        <tr>

            <th>Партнёры</th>

        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>

                <td class="text-left"><?=$row->name_partner; ?></td>
                <td><a href='<?= Url::Action("edit-thanks", "administration.about") ?>?id=<?= $row->id; ?>'>Редактировать</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete-thanks", "administration.about") ?>?id=<?= $row->id; ?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>