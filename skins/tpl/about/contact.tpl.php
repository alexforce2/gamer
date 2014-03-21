<?php
/*if (isset($_POST['tariff'])) {
    include $path . '/classes/modal.class.php';
    $objModalWindow = new ModalWindow($_POST, $_SESSION['user_data']);
    $objModalWindow->ChangeTarif();
}
include $path . '/libs/change-tarif.modal.php';*/
?>
<table id="header-profile">
    <tr>
        <td>
            <span><b>Пользователь:</b> <?=$_SESSION['user-data']['name']; ?></span>
        </td>
        <td class="header-profile-button">
            <a href="/advert/send"><img class="first-profile-button" src="/skins/img/butt-send-ad.png"/></a>
            <a href="/balance/up"><img src="/skins/img/butt-balance.png"/></a>
        </td>
    </tr>
</table>
<table height="460px">
    <tr>
        <td width="20%" style="vertical-align: top;">
            <div class="name-section-price">
                <b>Контакты</b>
            </div>
            <div class="info-section">
                <div>
                    Действующий тариф: <b>Профессионал</b><br/>
                    До <?php echo date("d.m.Y"); ?> <a href="#" onclick="actionChangeTariff()">Сменить тариф</a>
                </div>
            </div>

        </td>
        <td class="cell-profile">
            <div><span style="color:green; font-weight: bold; font-size: 16px;"><?=$data['success']; ?></span></div>
            <div><span style="color:red; font-weight: bold; font-size: 16px;"><?=$data['error']; ?></span></div>
            <div class="block-txt-price">
                <b>ICQ консультации,</b> №: 457108313, вы можете задать любые вопросы, касающиеся рынка недвижимости. По
                мере возможности мы ответим на них или подскажем где найти информацию, у нас на портале или вне нашего
                ресурса.<br/><br/>
                <b>По техническим вопросам</b> работы портала обращайтесь по адресу <a
                    href="mailto:support@metrosphera.ru">support@metrosphera.ru</a><br/><br/>

                <h2>ООО «Медиана»</h2><br/>
                Телефон: (342) 220-61-11 (многоканальный)<br/>
                Почтовый адрес: 614000, Россия, Пермь, Орджоникидзе, 41<br/>
                Юридический адрес: 614000, Россия, Пермь, Орджоникидзе, 41<br/><br/>

                По вопросам, связанных с <b>работой в личном кабинете:</b><br/>
                <b>Ведущий специалист отдела <span style="color:red">технической поддержки</span></b><br/>
                Русских Николай Гендрихович<br/>
                Т. (342) 2206-330, 2206-111<br/>
                E-mail: <a href="mailto:rus@metrosphera.ru">rus@metrosphera.ru</a><br/><br/>

                <div class="FL"><img src="http://www.metrosphera.ru/storage/site/images/11033nnh52legs5sn.jpg"/></div>
                <div style="padding: 5px 130px">
                    <b>Ведущий менеджер по рекламе</b><br/><br/>
                    Денис Геннадьевич Мырзин<br/>
                    Т. (342) 2206-330, 2206-111<br/>
                    Мобильный телефон: 8-982-489-78-82<br/>
                    ICQ: 377493861<br/>
                    E-mail: <a href="mailto:denis@metrosphera.ru">denis@metrosphera.ru</a>
                </div>
                <div class="clear"></div>
                <form action="" autocomplete="off" method="post" id="form-send-of-obj-realaty">
                    <h2>Форма обратной связи</h2><br/>
                    <textarea rows="5" id="message" name="message"></textarea><br/>
                    <input type="submit" value="Отправить"/>

                </form>
            </div>

        </td>
    </tr>
</table>