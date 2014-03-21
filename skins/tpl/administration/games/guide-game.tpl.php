<div>
    <h2><?=$data['game']->name;?></h2>
    <a href="/administration/games/main-page/<?=$data['game']->id;?>">Редактирование главной страницы</a><br>
    <?php
    foreach($data['rubrics'] as $rubric){ ?>
    <a href="/administration/games/game-rubric-articles/<?=$rubric['id']?>">Редактирование рубрики "<?=$rubric['rubric']?>"</a><br>
    <?php } ?>
<div>