<style>
    #codes span{border: 1px solid black; padding: 5px; cursor: pointer;}
    .avatar-comment{width: 75px; height: 75px; border-radius: 5px;}
    .table-comment td{}
    .info-comment{width: 130px; padding-right: 20px;}
    .info-comment b{position: relative; left: 10px;}
    .info-comment span{position: relative; left: 10px; color: #7f8c8d; font-size: 12px;}
    .text-comment{padding-right: 20px; max-width: 675px;}
    .menu-comment{border: 1px solid #e9e9e9; padding: 7px; font-size: 12px; position: absolute; right: 19px; background-color: #ffffff; width: 100px; z-index: 100}
    .icon-menu-comment{position: relative; width: 19px; height: 15px; cursor: pointer; background-image: url("/skins/img/interface/icon-menu-comment.png"); background-repeat: no-repeat}
    .menu-comment div{cursor: pointer;}
    .menu-comment-answer{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-quote{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -18px; padding-left: 15px;}
    .menu-comment-like{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-dislike{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px 5px; padding-left: 15px;}
    .menu-comment-remove{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -61px; padding-left: 15px;}
    .menu-comment-spam{background-image: url("/skins/img/interface/menu-comment.png"); background-repeat: no-repeat; background-position: 0px -83px; padding-left: 15px;}
    #send-comment{margin-left: 10px; background: #5cade2 !important; margin-right: 10px;}
    #text-comment{width: 99%; resize: none;  margin-top: 15px; z-index: 9}
    #action-answer{background-color: gray; margin-left: 160px; padding: 8px; opacity: 0.5;}
    #codes span{border: 1px solid black; padding: 5px; cursor: pointer;}
</style>
<div class="main">
    <p>Пользователь: <?= $data['winner-data']->nick; ?></p>
    <p>Победил в турнире: <?= $data['winner-data']->header; ?></p>
    <p>Сумма выигрыша: <?= $data['winner-data']->pay; ?></p>
    <p>Завершён <?= date("d.m.Y", $data['winner-data']->end_date) ?></p>
    <p>Просмотров <?= $data['winner-data']->count_views ?></p>



    <?php if (!empty($data['winner-data']->audio_link)) { ?>
        <p>Интервью с победителем</p>
        <audio controls preload="none" data-setup="{}">
            <source src="/storage/winner/tournament-winner-audio/<?= $data['winner-data']->audio_link ?>"
                    type="audio/mpeg"/>
        </audio>
    <? } ?>
    <?php if (!empty($data['winner-data']->video_link)) { ?>
        <p>Видео победителя</p>
        <video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster=""
               data-setup="{}">
            <source src="/storage/winner/tournament-winner-video/<?= $data['winner-data']->video_link ?>"
                    type='video/mp4'/>
        </video>
    <? } ?>
</div>
<p><?= $data['winner-data']->text; ?></p>
<? include $tplOther->likes; ?>
<!-- Подключение комментариев -->
<div id="<?=$data['winner-data']->id_ucg?>-3-GetUserLikesCommentsUCG">
    <div class="br-points"></div>
    <h3>Ответы</h3>
    <div class="content-comment"></div>
    <br class="clear">

    <?php if($_SESSION['auth'] == 1){?>
        <a href="javascript:void(0)" id="send-comment" class="left btn">Отправить</a>
        <div id="action-answer" class="hide right"></div>
        <br><br>
        <div id="codes">
            <span><b>B</b></span>
            <span><u>U</u></span>
            <span ><i>I</i></span>
            <span ><s>S</s></span>
        </div>
        <textarea id="text-comment"></textarea>
    <?}else{
        echo "<h3>Комментарии могут оставлять только зарегистрированные пользователи</h3>";
    }?>
</div>
<!-- Конец комментариев -->


<script type="text/javascript">
    $(document).ready(function () {
        initLikes();
    });
</script>




