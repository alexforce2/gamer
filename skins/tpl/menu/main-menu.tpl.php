<div class="main-div-menu">
    <div class="nav-bar nav-bar-inverse">
        <div class="nav-bar-inner">
            <div class="nav-container">
                <ul class="nav">
                    <li class="<?=($_SERVER['REQUEST_URI'] == '/') ? 'active':''; ?>">
                        <a class="menu-link" href="/">Главная</a>
                    </li>
<!--                    <li>-->
<!--                        <a href="/forum">Общение</a>-->
<!--                    </li>-->
                    <li class="<?=(substr($_SERVER['REQUEST_URI'], 0, 11) == '/tournament') ? 'active':''; ?>">
                        <a class="menu-link" href="#">Турниры</a>
                        <ul>
                            <li>
                                <a href="#">On-line турниры</a>
<!--                                <ul>-->
<!--                                    <li><a href="/tournament/?t=diablo-iii&id=455&page=internal">Diablo 3</a></li>-->
<!--                                </ul>-->
                            </li>
                            <li><a href="/tournament/winners-list">Победители турниров</a></li>
                            <!--                            <li>-->
                            <!--                                <a href="#">Of-line конкурсы</a>-->
                            <!--                                <ul>-->
                            <!--                                    <li><a href="#">Diablo 2</a></li>-->
                            <!--                                    <li><a href="#">Need for Speed Underground</a></li>-->
                            <!--                                    <li><a href="#">Risen</a></li>-->
                            <!--                                </ul>-->
                            <!--                            </li>-->
                            <!--                            <li><a href="#">Победители турниров</a></li>-->
                            <!--                            <li><a href="#">Статистика по турнирам</a></li>-->
                            <!--                            <li><a href="#">Информация для участников</a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="#">Чтиво</a>
                        <ul>
                            <!--                            <li><a href="#">Новости</a></li>-->
                            <li><a href="/guide/games">Игровые обзоры</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="search-menu">
        <input type="text" class="search-field" id="autocomplete-ajax" placeholder="Поиск"/>
        <div id="selction-ajax"></div>
    </div>
</div>