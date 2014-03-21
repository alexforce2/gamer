<?php
use classes\render;
use classes\url;
?>

<style>
    .text-left{text-align: left}
</style>
<h1>Список турниров</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("create-tournament", "administration.tournament") ?>">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table" style="width: 940px; text-align: center">
        <thead>
        <tr>

            <th>Заголовок</th>
            <th>Кол-во участников</th>
            <th>Действие</th>
        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>

                <td class="text-left"><?=$row->header; ?></td>
                <td><?=$row->count_users; ?></td>
                <td>
                    <a href='<?= Url::Action("edit-tournament", "administration.tournament") ?>?id=<?= $row->id ?>'>Редактироавть</a> |
                    <a data-action="delete" href='<?= Url::Action("delete-tournament", "administration.tournament") ?>?id=<?= $row->id ?>'>Удалить</a>
                </td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>