<script>

    $(function(){
        $('#phone-reg').poshytip({
            className: 'tip-black',
            showOn: 'focus',
            alignTo: 'target',
            alignX: 'inner-left',
            offsetX: 0,
            offsetY: 5,
            showTimeout: 100
        });
    });
</script>
<div class="left logo-games">
    <img src="/skins/img/logo-games.jpg"/>
</div>

<div class="welcome-txt left">
    <h3>Добро пожаловать</h3>

    <div style="width: 300px;">
        <span>GS11 - это уникальный ресурс, который даёт возможность каждому поучаствовать в игровых турнирах, вести свой личный игровой блог, общаться, и искать любимые игры.</span>
    </div>
    <div class="top-reg-input">
        <div id="ajax-result" style="position: relative; bottom: 10px;"></div>
        <input type="text" class="form-reg-input login-input-big" id="phone-reg" value="+7" size="12"
               title="Номер необходим <b>только</b> для авторизации на сайте.<br>Весь процесс полностью бесплатен.<br><br>Пример для России +7 908 2727647"/>
        <span id="login-reg-result"></span>
    </div>
    <div>
        <input type="password" class="form-reg-input" id="pass-reg" placeholder="Пароль"/>
        <span id="pass-reg-result"></span>
    </div>
    <div class="bottom-reg-input">
        <input type="password" class="form-reg-input" id="pass-reg-confirm" placeholder="Подтверждение пароля"/>
        <span id="pass-confirm-reg-result"></span>
    </div>
    <div class="left">
        <a id="registration" class="btn-reg">Продолжить</a>
    </div>
    <div class="btn-reg-txt">
        <span>После нажатия кнопки "Продолжить" Вам будет отправлено <b>бесплатное</b> SMS-сообщение с кодом подтверждения.</span>
    </div>
    <div class="clear"></div>
    <div style="width: 400px; font-size: 12px; margin-top: 25px;">
        <span>Для вас есть возможность:</span>
        <ul>
            <li>получать призы, играя в свои любимые игры;</li>
            <li>узнать неожиданно новые подробности о любимых играх;</li>
            <li>делиться с другими игроками лучшими моментами, пережитыми в виртуальном мире.</li>
        </ul>

    </div>

</div>
<div class="clear"></div>
<div class="hide">
    <div class="box-modal" id="modal-registration" style="width: 500px">
        <div class="header-modal">
            <b>Завершение регистрации аккаунта</b>

            <div class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                <img src="/skins/img/interface/close-modal.png"></div>
        </div>
        <div style="padding:15px; padding-bottom: 45px;">
            <img id="captcha-image" src="/skins/img/interface/confirm-sms-code.png" class="left">

            <div style="margin-left: 135px"><p>На ваш телефон было отправленно <b>бесплатное</b> смс сообщение с кодом
                    активаци.</p>
                <input type="text" class="form-login-input" id="code-reg" placeholder="Код активации"/><span
                    id="ajax-modal-result"></span><br><br>

                <div style="float: right"><a href="javascript:regComplete()" class="btn-login">Регистрация</a>
                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()"
                       class="btn-login">Отмена</a></div>
            </div>
        </div>
    </div>
</div>