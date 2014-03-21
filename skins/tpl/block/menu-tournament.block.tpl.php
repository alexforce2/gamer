<style>
    .nav-bar .left-nav > li{float: none;}
    .header-left-menu{color: #329e8c; padding:10px 0 7px 10px; font-size: 18px; border-bottom: 1px solid #4a5c6f; width: 175px;}
    .footer-left-menu{color: #ffffff; padding:10px 0 7px 10px; font-size: 12px; border-top: 1px solid #4a5c6f; width: 175px;}
    .nav-bar .left-nav > li > a {padding: 9px 10px 10px !important;}
    .icon-left-menu{padding-right: 5px; position: relative; top: 3px;}
</style>
<div class="left">
    <div class="main-div-menu">
        <div class="nav-bar nav-bar-inverse" style="width: 215px !important;">
            <div class="nav-bar-inner" style="border-radius: 2px;">
                <div class="nav-container">
                    <ul class="nav left-nav">
                        <li>
                            <div class="header-left-menu">Турниры</div>
                        </li>
                        <li class="<?=($_SERVER['REQUEST_URI'] == '/tournament/tournaments-list') ? 'active':''; ?>">
                            <a class="menu-link" href="/tournament/tournaments-list"><img class="icon-left-menu" style="" src="/skins/img/interface/about-company.png" alt="Турниры">Турниры</a>
                        </li>
                        <li class="<?=($_SERVER['REQUEST_URI'] == '/tournament/winners-list') ? 'active':''; ?>">
                            <a class="menu-link" href="/tournament/winners-list"><img class="icon-left-menu" style="" src="/skins/img/interface/about-offer.png" alt="Победители">Победители</a>
                        </li>
                        <!--<li class="<?=($_SERVER['REQUEST_URI'] == '/about/tariff') ? 'active':''; ?>">
                            <a class="menu-link" href="/about/tariff"><img class="icon-left-menu" style="" src="/skins/img/interface/about-offer.png" alt="Компания GS11">Победители</a>
                        </li>
                        <li class="<?=($_SERVER['REQUEST_URI'] == '/about/tariff') ? 'active':''; ?>">
                            <a class="menu-link" href="/about/tariff"><img class="icon-left-menu" style="" src="/skins/img/interface/about-offer.png" alt="Компания GS11">Таблица выплат</a>
                        </li>-->

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>