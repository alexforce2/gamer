<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title; ?></title>
    <meta name="keywords" content="<?=$this->keywords; ?>" />
    <meta name="description" content="<?=$this->description; ?>" />
    <link type="text/css" rel="stylesheet" href="/skins/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/colorbox.css"/>
    <link type="text/css" rel="stylesheet" href="/skins/css/video-js.css"/>
    <?=$arrCss;?>

    <script type="text/javascript" src="/skins/js/history.js?type=/&redirect=true&basepath=/skins/"></script>
    <script type="text/javascript" src="/skins/js/jquery.min.js"></script>
    <script type="text/javascript" src="/skins/js/script.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.poshytip.js"></script>
    <script type="text/javascript" src="/skins/js/arctic-modal.min.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.customSelect.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.colorbox-min.js"></script>
    <?=$arrJs;?>


</head>
<style>
    .footer-txt{margin-left: 18px; color: #878787; font-size: 13px;}
    .footer-txt a{color: #878787; text-decoration: none;}
    #footer{padding-top: 10px;}
    .footer-logo{position: relative; top:3px;}
    .footer-certificate{position: relative; bottom:2px; height: 40px;}
</style>
<script>
$(document).ready(function(){
    $('#remember-check').toggleClick(function(){
        $('#remember-check').html('<img src="/skins/img/interface/check-remember-enabled.png" /> <span style="color: #34c0a3">Запомнить</span><input type="hidden" id="remember" value="true">')
    }, function(){
        $("#remember").remove();
        $('#remember-check').html('<img src="/skins/img/interface/check-remember-disabled.png" /> <span>Запомнить</span>')
    })
})

    </script>
<body>
<div id="header">
    <div class="left"  style="width: 505px;">
        <a href="/">
            <img src="/skins/img/logo.png" alt="Главная страница GS11" title="Главная страница GS11"/>
        </a>
    </div>

    <div class="form-login left">
        <input type="text" class="form-login-input" id="login-in" placeholder="Email или Телефон"/>
        <input type="password" class="form-login-input" id="pass-in" placeholder="Пароль"/>
        <a href="javascript:loginIn()" class="btn-login">Вход</a>
        <div class="form-login-remember">
            <div id="ajax-login-result" class="left"></div>
            <div style="position: relative; left:172px;">
                <div id="remember-check" class="left">
                    <img src="/skins/img/interface/check-remember-disabled.png" />
                    <span>Запомнить</span>
                </div>
                <a href="/restore">Забыли пароль?</a>
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

<!--        <a href="/about/certificate">-->
<!--            <img class="right footer-certificate" src="/skins/img/certificate.png" alt="Сертификат GS11"-->
<!--                 title="Сертификат GS11"/>-->
<!--        </a>-->
<div>
<div class="clear"></div>
</body>
</html>