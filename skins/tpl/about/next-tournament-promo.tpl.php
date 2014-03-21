<div id="main">
    <div id="slider">
        <div id="internal-slider">
            <div id="social-buttons">
                <a href="#"><img src="/skins/img/youtube.png" alt="YouTube" /></a>
                <a href="#"><img src="/skins/img/google-plus.png" alt="Google-plus" /></a>
                <a href="#"><img src="/skins/img/skype.png" alt="Skype" /></a>
                <a href="#"><img src="/skins/img/vk.png" alt="Vkontakte" /></a>
                <a href="#"><img src="/skins/img/fb.png" alt="FaceBook" /></a>
                <a href="#"><img src="/skins/img/tw.png" alt="Twitter" /></a>
            </div>
            <div id="select">
                <a id="prev-btn" class="slider-action" style="background:url(/skins/img/slider-btn.png) top 0 left 0;" page="legend-tournament" ></a>
                <a id="next-btn" class="slider-action"  style="background:url(/skins/img/slider-btn.png) top 100% left 100%;" page="winner"></a>
            </div>
            <img src="/skins/img/slider2.jpg" alt="slider"/>
        </div>

    </div>
    <div class="near-tournaments">
        <?$cssClass = array("left-game","center-game","right-game");
        foreach($data['near_tournaments'] as $key=>$value){?>
        <div class="<?echo $cssClass[$key]?>">
            <h3><a href="#"><?echo $value['title']  = substr($value['title'],0,27)."..."?></a></h3>
            <p>С 1 июля, призовой фонд 1000 р</p>
            <p class="img-center"><img src="/storage<?echo $value['source_img_s']?>" /></p>
        </div>
        <? } ?>
    </div>

    <div class="middle-content">
            <div class="left-middle">
                <span>Активных участников</span>
                <img src="/skins/img/inquiries.png"/>
                <p>+<?echo $data['count_users'][0][0]?></p>
            </div>
            <div class="right-middle">
                <a href="#">Принять участие</a>
            </div>
            <div class="center-middle">
                <p>абсолютно</p>
                <span>бесплатное</span>
                <p>участие</p>
            </div>
    </div>
    <div class="bottom-content">
        <p>Участие в турнирах возможно только в том случае, если вы зарегистрировались на нашем <a href="/">сайте</a>. Это дает вам возможность
            не только участвовать в турнирах, но и вести блоги по пройденным играм, а так же многое другое... </p>
    </div>
</div>