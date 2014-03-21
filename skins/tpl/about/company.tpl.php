
<style>
    .content-about {margin-left: 250px; position: relative; bottom: 15px}
    .lead td{text-align: center; width: 300px;}
    .lead div{position: relative; bottom: 10px;}
    .lead span{color: #52c7b0}
    .director, .movie-maker, .design{font-size:11px; border: 1px solid #e9e9e9; width: 160px; position: absolute !important; z-index: 100; left: 195px; text-align: left; padding: 15px; background-color: white;}
</style>
<script>
    function showInfoLead(param){
        (param == "out") ? $(".director, .movie-maker, .design").hide() :  $("."+param).show();
    }

</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-about.block.tpl.php'; ?>

<div class="content-about">
    <h2 style="color: #52c7b0">Компания</h2>
    <h2 style="text-align: center;">Маленькая команда - качественная работа</h2>
    <table class="lead">
        <tr>
            <td>
                <h4>Василий Баранов</h4>
                <div>
                    <img onmouseover="showInfoLead('director')" onmouseout="showInfoLead('out')" src="/skins/img/bvn.jpg" alt="Василий Баранов"><br>
                    <span>Директор</span>
                    <div class="hide director" style="bottom: -32px;">
                        <span>Основатель компании - GS11</span><br>
                        <span style="color: #34495e">Главный разработчик.</span><br>
                        <span style="color: #34495e">Окончил Пермский Радиотехнический колледж имени А.С. Попова. На протяжении многих лет профессионально занимается программированием.</span><br>
                    </div>
                </div>
            </td>
            <td>
                <h4>Анастасия Старцева</h4>
                <div>
                    <img onmouseover="showInfoLead('design')" onmouseout="showInfoLead('out')" src="/skins/img/sau.jpg" alt="Анастасия Старцева"><br>
                    <span>Дизайнер</span>
                    <div class="hide design" style="bottom: -15px;">
                        <span>Дизайнер компании - GS11</span><br>
                        <span style="color: #34495e">Главный дизайнер.</span><br>
                        <span style="color: #34495e">Увлечена современной модой. Долгое время занималась конструированием одежды, на сегодняшний день профессионально занимается дизайном.</span><br>
                    </div>
                </div>
            </td>
            <td>
                <h4>Кирилл Кузнецов</h4>
                <div>
                    <img onmouseover="showInfoLead('movie-maker')" onmouseout="showInfoLead('out')" src="/skins/img/kkv.jpg" alt="Кузнецов Кирилл"><br>
                    <span style="color: #52c7b0"></span>
                    <div class="hide movie-maker" style=" bottom: -8px;">
                        <span>Мувимейкер компании - GS11</span><br>
                        <span style="color: #34495e">Главный мувимейкер.</span><br>
                        <span style="color: #34495e">Окончил Пермский Радиотехнический колледж имени А.С. Попова. Увлечён спортом (WorkOut), профессионально занимается видеомонтажом</span><br>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div style="padding-top: 15px;">
        <b>Наша компания включает в себя:</b><br>
        <img src="/skins/img/interface/closed-marker.png" style="padding-right: 10px;">Более 10 сотрудников, некоторые из которых проживают в странах СНГ. Наши сотрудники заинтересованы в развитии проекта, и занимаются его продвижением, дизайном, разработкой, написанием wiki по играм, и внедрением новых технологий.
    </div>
    <div style="padding-top: 15px;">
        <b>Для чего мы трудимся:</b><br>
        <img src="/skins/img/interface/closed-marker.png" style="padding-right: 10px;">Сбалансированное присутствие на всеобщем игровом пространстве: в интернете очень много сайтов, посвящённых игровой тематике, но пользователь всё же ищет в поисковиках нужную для него информацию. Одной из важных задач нашей компании является уникальный портал, на котором будет находиться вся информация о индустрии PC игр.
    </div>
    <div style="padding-top: 15px;">
        <b>Что вас ждёт:</b><br>
        <img src="/skins/img/interface/closed-marker.png" style="padding-right: 10px;"> Мы стараемся создать обширную базу знаний игр для широкого спектра различных игровых предпочтений, чтобы многочисленные поклонники PC игр не забывали о тех прекрасных моментах игрового мира, которые были с нами всё наше детство, а также это будет история для будущего поколения.
    </div>
    <div style="padding-top: 15px;">
        <b>Наше будущее:</b><br>
        <img src="/skins/img/interface/closed-marker.png" style="padding-right: 10px;">Мы будем изо всех сил стараться занять лидерство по проведению турниров и конкурсов для on-line и off-line игр.
    </div>
    <div style="padding-top: 20px;" class="left">
        Дата основания сайта: ноябрь 2012<br>
        Регистрация: 17 ноября 2012<br>
    </div>
    <?// include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/share-soc.block.tpl.php"; ?>

    <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
<!--    <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir"></div>-->
    <script>
       /* new Ya.share({
            element: 'ya_share',
            elementStyle: {
                'type': 'none',
                'quickServices': ['facebook','twitter','odnoklassniki','vkontakte','moimir']
            },
            title: 'Allsoft 8 лет! — Акция с 11 по 31 мая!',
            description: 'Пройдите праздничный тест и примите участие в розыгрыше',
            link: 'http://allsoft.ru/promo/allsoft8let/',
            serviceSpecific: {
                twitter: {
                    title: 'Allsoft 8 лет! — Акция с 11 по 31 мая!'
                }
            }
        });*/
    </script>
    <span id="ya_share_normal"></span>
    <span id="ya_share_high"></span>
    <span id="ya_share_low"></span>
    <span id="ya_share"></span>
</div>