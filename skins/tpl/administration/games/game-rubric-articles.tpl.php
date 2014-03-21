<?php
use classes\render;
use classes\url;
?>

<h1>Статьи рубрики "<?=$data['game-rubric']['rubric']?>" -> "<?=$data['game-rubric']['game']?>"</h1>
<div style="height: 45px">

    <div class="right">
        <a name="create_item" id="create_item" href="<?= Url::Action("create-rubric-article", "administration.games")?><?=$data['game-rubric']['id_rubric']?>">Добавить</a>
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
                <td><a href='<?= Url::Action("edit-game-rubric-article", "administration.games") ?>?id=<?=$row['id']?>&id-game=<?=$data['game-rubric']['id']?>'>Редактироавть</a>
                </td>
                <td><a data-action="delete"
                       href='<?= Url::Action("delete-rubric-article", "administration.games") ?>?id-article=<?= $row['id'] ?>&id=<?=$data['game-rubric']['id_rubric']?>'>Удалить</a></td>
            </tr>
        <? }?>
        </tbody>
    </table>
</div>