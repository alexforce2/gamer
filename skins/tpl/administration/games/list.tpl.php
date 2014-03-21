<?php
use classes\render as Render;
use classes\url as Url;

?>
<h1>Игровые обзоры</h1>
<div style="height: 45px">
    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("create", "administration.games") ?>">Добавить</a>
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

        <? foreach ($data["rows"] as $row) { ?>
            <tr>
                <td><?=Render::FormatDate($row['date'])?></td>
                <td class="text-left"><?=$row['header']?></td>
                <td><a href='<?= Url::Action("edit", "administration.games") ?>?id=<?= $row['id'] ?>'>Редактироавть</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete", "administration.games") ?>?id=<?= $row['id'] ?>'>Удалить</a></td>
            </tr>
        <? }?>
    </table>
</div>