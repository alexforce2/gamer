<style>
    .content-guide-games{position:relative; bottom: 17px;}
    .table-video-screen-game{text-align: left}
    .table-screen img{margin-left: 5px; width: 170px; height: 150px;}
</style>
<script>
    $(document).ready(function(){
        //Examples of how to assign the Colorbox event to elements
        $(".obj-img").colorbox({rel:'group1'});
        $(".gallery-tinymce").colorbox({rel:'group2'});
    });
</script>
<img class="left" style="padding-right: 25px; width: 400px" src="/storage<?=$data['obj']->source_img_b; ?>" alt="<?=$data['obj']->name; ?>" title="<?=$data['obj']->name; ?>" />
<div class="content-guide-games">
    <h3><?=$data['obj']->name; ?></h3>
    Издатель: <a href="<?=$data['obj']->publisher_link; ?>" target="_blank"><?=$data['obj']->publisher; ?></a><br>
    Издатель в россии: <a href="<?=$data['obj']->publisher_russia_link; ?>" target="_blank"><?=$data['obj']->publisher_russia; ?></a><br>
    Разработчик: <a href="<?=$data['obj']->developer_link; ?>" target="_blank"><?=$data['obj']->developer; ?></a><br>
    Сайт: <a href="<?=$data['obj']->official_site_link; ?>" target="_blank"><?=$data['obj']->official_site; ?></a><br>
    Игровой движок: <span><?=$data['obj']->game_engine; ?></span><br>
    Жанр: <span><?=$data['obj']->genre; ?></span><br>
    Режим игры: <span><?=$data['obj']->game_mode; ?></span><br>
    Распростаранение: <span><?=$data['obj']->distribution; ?></span><br>
    Системные требования: <br><span>ОС: <?=$data['obj']->sr_os; ?></span><br>
    <span>ОЗУ: <?=$data['obj']->sr_ram; ?></span><br>
    <span>CPU: <?=$data['obj']->sr_cpu; ?></span><br>
    <span>Видеокарта: <?=$data['obj']->sr_video; ?></span><br>
    <span>HDD: <?=$data['obj']->sr_hdd; ?></span><br>

    <span>
        <?=date("d", $data['obj']->date_release_world);?>
        <?=$this->GetDateRu("month", date("d.m.Y", $data['obj']->date_release_world));?>
        <?=date("Y", $data['obj']->date_release_world);?>
    </span><br>
    Дата выхода в россии:
    <span>
        <?=date("d", $data['obj']->date_release_russia);?>
        <?=$this->GetDateRu("month", date("d.m.Y", $data['obj']->date_release_russia));?>
        <?=date("Y", $data['obj']->date_release_russia);?>
    </span><br><br><br>
    <?=$data['obj']->text; ?>
</div>

<table class="table-video-screen-game">
    <tr>
        <th>
            Видео трейлер
        </th>
        <th>
            Скриншоты
        </th>
    </tr>
    <tr>
        <td style="padding-right: 5px; width: 420px;">
            <div class="" style="position: relative; bottom: 2px;">
                <video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster="<?=$data['obj']->video_img; ?>" data-setup="{}">
                    <source src="<?=$data['obj']->video_link; ?>" type='video/mp4' />
                </video>
            </div>
        </td>
        <td>
            <? foreach($data['obj-img'] as $r){?>
                <div class="left table-screen"><a class="obj-img" href="<?=$r->screenshot_b; ?>" ><img src="<?=$r->screenshot_s; ?>" /></a></div>
            <?}?>
        </td>
    </tr>
    <table>
        <div style="width: 100%">
            <? foreach($data['obj-rubric'] as $r){?>
                <div style="text-align: center; float: left; padding: 10px;"><a style="color: #000000" href="<?=$r->id; ?>" ><img src="<?=$r->rubric_img_b; ?>" /><br><b><?=$r->rubric;?></b></a></div>
            <?}?>
        </div>

