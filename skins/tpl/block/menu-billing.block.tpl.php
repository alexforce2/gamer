<style>
    .nav-bar .billing-nav > li{float: none;}
</style>
<div class="main-div-menu">
    <div class="nav-bar nav-bar-inverse" style="width: 215px !important;">
        <div class="nav-bar-inner" style="border-radius: 2px;">
            <div class="nav-container">
                <ul class="nav billing-nav">
                    <li class="<?=($_SERVER['REQUEST_URI'] == '/billing') ? 'active':''; ?>">
                        <a class="menu-link" href="/billing">Пополнение счёта</a>
                    </li>
                    <li class="<?=($_SERVER['REQUEST_URI'] == '/billing/tariff') ? 'active':''; ?>">
                        <a class="menu-link" href="/billing/tariff">Выбор тарифа</a>
                    </li>
<!--                    <li class="<?=($_SERVER['REQUEST_URI'] == '/billing/transfer') ? 'active':''; ?>">-->
<!--                        <a href="/billing/transfer">Перевод средств</a>-->
<!--                    </li>-->
<!--                    <li class="<?=($_SERVER['REQUEST_URI'] == '/billing/receiving') ? 'active':''; ?>">-->
<!--                        <a href="/billing/receiving">Вывод средств</a>-->
<!--                    </li>-->
                    <li class="<?=($_SERVER['REQUEST_URI'] == '/billing/history') ? 'active':''; ?>">
                        <a class="menu-link" href="/billing/history">История операций</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>