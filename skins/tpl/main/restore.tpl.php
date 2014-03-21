<style>
    .btn-restore-email{padding: 10px 53px;}
    .btn-reg{cursor: pointer;}
    #restore-result{font-weight: bold; color: red;}
    .restore-content{margin: 0 auto; width: 665px; padding: 45px 0; line-height: 3.1em}
    .restore-content-pass{margin: 0 auto; width: 280px; line-height: 3.1em; margin-bottom: 10px;}
    #reset-email, #reset-phone{width: 200px;}
    #img-reset-phone, #img-reset-email{padding-right: 12px;}
    #phone-restore-link, #email-restore-link{font-size: 18px; text-decoration: none; color: #34495e}
    .result-restore{margin-left: 10px;}
    .box-modal2 {position: relative; width: 500px;background: #fff; padding: 0px !important; color: #3c3c3c; font: 14px/18px; border-radius: 4px;}
    .bottom-reg-input{margin-bottom: 5px;}
</style>
<?php
/*****        Выводим форму смены пароля в случае успешного перехода по ссылке       *****/
if (isset($data['exist-pass-restore']) && $data['exist-pass-restore'] == true) {
    ?>
    <div class="restore-content-pass">
        <h2>Установить новый пароль</h2>

        <div>
            <input type="password" class="form-reg-input" id="pass-reg" placeholder="Новый пароль"/>
            <span id="pass-reg-result"></span>
        </div>
        <div class="bottom-reg-input">
            <input type="password" class="form-reg-input" id="pass-reg-confirm" placeholder="Подтверждение пароля"/>
            <span id="pass-confirm-reg-result"></span>
        </div>
        <div id="ajax-result" style="font-weight: bold; color: red; "></div>
        <div>
            <a id="change-pass-restore" class="btn-reg">Продолжить</a>
        </div>

    </div>
    <?
    /*****        Выводим сообще об ошибке в случае неверных данных       *****/
} else if (isset($data['exist-email-restore']) && $data['exist-email-restore'] == false) {
    ?>
    <b style="color:red">Восстановления пароля невозможно, если вам не удаётся восстановить пароль с помощью наших
        инструкций, напишите нам сообщение (Ссылка на форму сообщения)</b>
    <?
    /*****        Основная форма восстановления пароля       *****/
} else {
    ?>
    <div class="restore-content">
        <span style="font-size: 26px">Восстановить пароль можно двумя способами:</span><br>


        <a id="phone-restore-link" href="javascript:restoreType('phone', 'img-reset-phone')">
            <img id="img-reset-phone" class="closed" src="/skins/img/interface/closed-marker.png"/>
            С помощью отправки бесплатного смс сообщения с кодом на ваш телефон
        </a>

        <div class="hide reset-phone">
            <input type="text" id="phone-restore" placeholder="+7">
            <span class="result-restore"></span>
        </div>

        <a id="email-restore-link" href="javascript:restoreType('email', 'img-reset-email')">
            <img id='img-reset-email' class="closed" src="/skins/img/interface/closed-marker.png"/>
            С помощью электронной почты
        </a>

        <div class="hide reset-email">
            <input type="text" id="email-restore" placeholder="E-mail">
            <span class="result-restore"></span>
        </div>
        <div class="hide" id="btn-next-restore">
            <div class="right">
                <a id="restores" onclick="sendRestores()" class="btn-reg">Продолжить</a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<? } ?>

<div class="clear"></div>
<script type="text/javascript">

    function restoreType(param, imgId){
        if($("#"+imgId).hasClass('closed')){
            $("#"+imgId).attr("src", "/skins/img/interface/open-marker.png").removeClass('closed');
            $("#btn-next-restore").show();
        }else{
            $("#btn-next-restore").hide();
            $("#"+imgId).attr("src", "/skins/img/interface/closed-marker.png").addClass('closed');
        }

        if(param == 'phone'){
            $(".reset-phone").slideToggle("fast");
            $(".reset-email").slideUp("fast");
            $("#email-restore").val('');
            $(".result-restore").html("");
            $("#img-reset-email").attr("src", "/skins/img/interface/closed-marker.png").addClass('closed');
        } else if(param == 'email'){
            $(".result-restore").html("");
            $(".reset-email").slideToggle("fast");
            $(".reset-phone").slideUp("fast");
            $("#phone-restore").val('');
            $("#img-reset-phone").attr("src", "/skins/img/interface/closed-marker.png").addClass('closed');
        }
    }

    function restoreSendEmail(){
        var captcha = $("#code-captcha-input").val();
        var email = $("#email-restore").val();
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class':'model', 'method': 'RestoreSendEmail', 'code-captcha-input': captcha, 'email': email},
            beforeSend: function(){},
            success: function(data){
                debugger;
                if(data == false){
                    changeCaptcha();
                    $("#code-captcha-input").val('');
                }else{
                    $(".box-modal").html('<div class="box-modal" style="width: 500px">' +
                        '<div class="header-modal">' +
                        '<b>Восстановление аккаунта</b>' +
                        '<div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">' +
                        '<img src="/skins/img/interface/close-modal.png"></div></div>' +
                        '<div style="padding:15px; padding-bottom: 45px;">' +
                        '<img id="captcha-image" src="/skins/img/interface/restore-mail.png" class="left" style="padding-right: 30px;">' +
                        '<div style="margin-left: 15px"><span>На адресс вашей электронной почты <a style="color: #6eceb7 !important;" href="mailto:'+email+'">'+email+'</a> были высланны инструкции по смене пароля</span> ' +
                        '<div style="margin-top: 15px; font-size: 13px;"><span>Если сообщение не пришло в течении нескольких минут, проверьте спам - фильр или отправьте <a style="color: #6eceb7 !important;" href="javascript:closeModalAll()">снова</a></span></div></div>' +
                        '</div></div>');

                }

            }
        });
    }

    function sendRestores(){
        if($("#phone-restore").is(":visible")){
            var phone = $("#phone-restore").val();
            method = 'restoreSendPhone';
        }else{
            var email = $("#email-restore").val();
            method =  'restoreSendEmail';
        }
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'method': "RestoreExistUser", 'type-class': 'model', 'phone': phone, 'email': email},
            beforeSend: function(){
                $(".result-restore").html('<img class="ajax" src="/skins/img/ajax.gif">');
            },
            success: function(data){
                $(".ajax").remove();
                exsistLogin = data.split("#");
                if(exsistLogin[1] == 1){
                    $(".result-restore").append("<b style='color: red'>Пожалуйста, убедитесь, что правильно ввели телефон.</b>");
                }else if(exsistLogin[1] == 2){
                    $(".result-restore").append("<b style='color: red'>Пожалуйста, убедитесь, что правильно ввели email.</b>");
                }else{
                    $.arcticmodal({
                        type: 'ajax',
                        url: document.location.href,
                        ajax: {
                            type: 'POST',
                            cache: false,
                            success: function(data, el, responce){
                                $(".box-modal").html('<div class="box-modal" style="width: 500px">' +
                                    '<div class="header-modal">' +
                                    '<b>Восстановление аккаунта</b>' +
                                    '<div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">' +
                                    '<img src="/skins/img/interface/close-modal.png"></div></div>' +
                                    '<div style="padding:15px; padding-bottom: 45px;">' +
                                    '<img id="captcha-image" src="/skins/img/interface/restore-mail.png" class="left">' +
                                    '<div style="margin-left: 15px">На адресс вашей электронной почты <a href="mailto:'+email+'">'+email+'</a> ' +
                                    'Если сообщение не пришло в течении несколькинх минут, проверьте спам - фильр или отправьте <a href="javascript:closeModalAll()">снова</a></div>' +
                                    '</div></div>');
                                data.body.html($('<div class="box-modal" style="width: 500px">' +
                                    '<div class="header-modal">' +
                                    '<b>Восстановление аккаунта</b>' +
                                    '<div  class="box-modal_close arcticmodal-close">' +
                                    '<img src="/skins/img/interface/close-modal.png"></div></div>' +
                                    '<div style="padding:15px; padding-bottom: 45px;">' +
                                    '<a href="javascript:changeCaptcha()">' +
                                    '<img id="captcha-image" src="/main/captcha" width="180" height="100" class="left"></a>' +
                                    '<span style="margin-left: 15px">Введите текст с картинки</span>' +
                                    '<input type="text" class="form-login-input" style="margin: 10px 0 0 15px" id="code-captcha-input" placeholder="Код с картинки"/><br><br>' +
                                    '<div style="float: right"><a href="javascript:'+method+'()" class="btn-login">Продолжить</a>' +
                                    '<a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>' +
                                    '</div></div>'));
                            }
                        }
                    });
                }

            }
        });
    }
    function restoreSendPhone(){
        var captcha = $("#code-captcha-input").val();
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class':'model', 'method': 'RestoreSendPhone', 'code-captcha-input': captcha},
            beforeSend: function(){},
            success: function(data){
                if(data == false){
                    changeCaptcha();
                    $("#code-captcha-input").val('');
                }else{
                    $(".box-modal").html('<div class="box-modal" style="width: 500px">' +
                        '<div class="header-modal">' +
                        '<b>Восстановление аккаунта</b>' +
                        '<div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">' +
                        '<img src="/skins/img/interface/close-modal.png"></div></div>' +
                        '<div style="padding:15px; padding-bottom: 45px;">' +
                        '<img id="captcha-image" src="/skins/img/interface/confirm-sms-code.png" class="left">' +
                        '<div style="margin-left: 135px"><p>На ваш телефон было отправленно <b>бесплатное</b> смс сообщение с кодом активаци.</p>' +
                        '<input type="text" class="form-login-input" id="code-restore" placeholder="Код активации"/><span id="ajax-modal-result"></span><br><br>' +
                        '<div style="float: right"><a href="javascript:restoreConfirmPhone()" class="btn-login">Продолжить</a>' +
                        '<a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>' +
                        '</div></div>');
                }

            }
        });
    }
    function restoreConfirmPhone(){
        var codeRestore = $('#code-restore').val();
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'method': 'RestoreConfirmPhone', 'type-class': 'model', 'code-restore': codeRestore},
            beforeSend: function(){
                $('#ajax-modal-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
            },
            success: function(data){
                $("#ajax").remove();
                (data == "code-false") ? $('#ajax-modal-result').text("Код указан неверно") : location.reload();
            }
        });
    }

</script>