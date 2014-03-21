<?php
use classes\render;
use classes\url;
?>


<h1>Новости</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("create-news", "administration.press") ?>">Добавить</a>
    </div>
</div>

<div id="list">
    <table class="list-table">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Заголовок</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <? foreach ($data as $row) { ?>
            <tr>
                <td><?=Render::FormatDate($row->date)?></td>
                <td class="text-left"><?=$row->header; ?></td>
                <td><a href='<?= Url::Action("edit-news", "administration.press") ?>?id=<?= $row->id; ?>'>Редактироавть</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete", "administration.press") ?>?id=<?= $row->id; ?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>