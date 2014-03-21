<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title; ?></title>
    <meta name="keywords" content="<?= $this->keywords; ?>"/>
    <meta name="description" content="<?= $this->description; ?>"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/jquery.stars.rating.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/stars.rating.styles.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/colorbox.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/video-js.css"/>
    <?=$arrCss;?>
<!--            <link type="text/css" rel="stylesheet" href="/skins/css/ui.css" />-->

    <script type="text/javascript" src="/skins/js/jquery.min.js"></script>
    <script type="text/javascript" src="/skins/js/history.js?type=/&redirect=true&basepath=/skins/"></script>
    <script type="text/javascript" src="/skins/js/jquery.poshytip.js"></script>
    <script type="text/javascript" src="/skins/js/arctic-modal.min.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.customSelect.js"></script>
    <script type="text/javascript" src="/skins/js/custom_radio.js"></script>
    <script type="text/javascript" src="/skins/js/history.js"></script>
    <script type="text/javascript" src="/skins/js/custom_checkbox_and_radio.js"></script>
    <script type="text/javascript" src="/skins/js/script.js"></script>
    <script type="text/javascript" src="/skins/js/chat_scripts.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="/skins/js/service.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.rating.js"></script>
    <script type="text/javascript" src="/skins/js/multiupload.js"></script>
    <script type="text/javascript" src="/skins/js/likes.js"></script>
    <script type="text/javascript" src="/skins/js/comments.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.colorbox-min.js"></script>
    <?=$arrJs;?>


</head>
<body>
<style>
    .icon-profile{margin: 5px 0 0 5px}
    .icon-profile-bottom{margin: 1px 0 0 5px}
    .form-user-top{width: 100px; height: 71px; border: 1px solid #989ea8;}
    .form-msg-top{width: 100px; height: 51px; border: 1px solid #cbcfd4; text-align: center; font-size: 12px; line-height: 1em; }
    .form-msg-top a{position:relative; top:12px; color: #3d4a5d; text-decoration: none;}
    .img-user-small{float: right; margin: 5px;}

    .footer-txt{margin-left: 18px; color: #878787; font-size: 13px;}
    .footer-txt a{color: #878787; text-decoration: none;}
    #footer{padding-top: 10px;}
    .footer-logo{position: relative; top:3px;}
    .footer-certificate{position: relative; bottom:2px; height: 40px;}
</style>

<div id="header">

    <div class="left">
        <a href="/">
            <img src="/skins/img/logo.png" alt="Главная страница GS11" title="Главная страница GS11"/>
        </a>
    </div>
    <div class="right">
<!--        <div class="form-msg-top left">-->
<!--            <a href="#">1 новое сообщение</a>-->
<!--        </div>-->
        <div class="icon-profile left" style="position: relative; right: 15px;">
            <a href="/billing" style="text-decoration: none">
                <img class="left" src="/skins/img/interface/mini-profile-billing.png"/>
                <span style="font-size: 14px; margin-left: 5px; padding-right: 5px; color: #364b60; font-weight: bold" class="left">Баланс</span>
                <div class="right" style="background-color: #1abc9c;padding: 3px 10px;color: white;border-radius: 3px; position: relative; bottom: 3px;"><?=$_SESSION['user-data']['balance'];?> руб.</div>
            </a>
        </div>
        <div class="form-user-top left" <?=(substr($_SERVER['REQUEST_URI'], 0, 8) == '/profile') ? 'style="width:30px; !important"':''; ?>>
            <?php if(substr($_SERVER['REQUEST_URI'], 0, 8) != '/profile') {
                if($this->user['img_avatar'] == ""){
                    $avatar = ($this->user['sex'] == 1) ? "/skins/img/m.jpg" : "/skins/img/w.jpg";
                }
                else
                {
                    $avatar = (strlen($this->user['img_avatar']) > 20) ? $this->user['img_avatar'] : "/storage" . $this->user['img_avatar'] . ".jpg";
                }
                ?>
            <a href="/profile"><img class="img-user-small" style="width: 67px; height: 60px;" src="<?=$avatar?>" /></a>
            <? } ?>
            <div class="icon-profile">
                <a href="javascript:alert('сообщения')">
                    <img src="/skins/img/interface/mini-profile-msg.jpg" title="Мои сообщения"/>
                </a>
            </div>
            <div class="icon-profile-bottom">
                <a href="javascript:alert('новая запись в базу форма')">
                    <img src="/skins/img/interface/mini-profile-send-msg.jpg" title="Новая запись в базу"/>
                </a>
            </div>
            <div class="icon-profile-bottom">
                <a href="/logout">
                    <img src="/skins/img/interface/mini-profile-exit.jpg" title="Выход"/>
                </a>
            </div>
        </div>
    </div>

</div>
<div class="clear"></div>
<? include $tplAuth->menu; ?>
<div class="clear"></div>
<? include $contentView; ?>
<div class="clear"></div>


<div id="footer">
    <img src="/skins/img/logo-footer.png" alt="GS11" class="left footer-logo"/>

    <div class="footer-txt left">
        <span>
            <a href="/about/company">О нас</a> |
            <a href="/about/promo?page=winner">Промо</a> |
            <a href="/about/help">Помощь</a> |
            <a href="/about/offer">Офрета</a> |
            <a href="/about/tariff">Тарифы</a> |
            <a href="/about/contacts">Отправить отзыв</a> |
            <a href="http://vk.com/g_s11">Мы вконтакте</a>
        </span><br>
        <span>&copy; Ltd GS11, <?=date("Y")?>. Все наши услуги не облагаются НДС.</span>
    </div>
    <!--<a href="/about/certificate">
        <img class="right footer-certificate" src="/skins/img/certificate.png" alt="Сертификат GS11"
             title="Сертификат GS11"/>
    </a>-->

    <div>
        <div class="clear"></div>
</body>
</html>