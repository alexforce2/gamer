// todo Надо удалять сообщение Пожалуйста, убедитесь, что правильно ввели данные
// todo Исправить ошибку с вёрсткой (текст в полях наезжают на иконки)
// todo Доработать вёрстку иконки в полях сделать более чёткие
// todo В некоторых случаях модальное окно на крестик не закрывается

/*
* ПРОВЕРКА НА ПУСТОЕ ЗНАЧЕНИЕ
*/
function empty(data) {
    return (typeof(data) == 'undefined' || data == null || data == '' )
}

function addSoc(a){
    h=encodeURIComponent(window.location.href+window.location.hash);
    t=encodeURIComponent(document.title);
    if(a==1)h='vkontakte.ru/share.php?url='+h+'&title='+t;
    else if(a==2)h='odnoklassniki.ru/dk?st.cmd=addShare&st.s=1000&st._surl='+h+'&tkn=3009';
    else if(a==3)h='www.livejournal.com/update.bml?mode=full&subject='+t+'&event='+h;
    else if(a==4)h='twitter.com/timeline/home?status='+t+'%20'+h;
    else if(a==5)h='www.facebook.com/share.php?u='+h;
    else if(a==6)h='wow.ya.ru/posts_share_link.xml?url='+h+'&title='+t;
    else if(a==7)h='connect.mail.ru/share?url='+h+'&title='+t+'&description=&imageurl=';
    else if(a==8)h='moikrug.ru/share?ie=utf-8&url='+h+'&title='+t+'&description=';
    else return;
    window.open('http://'+h,'Soc','screenX=100,screenY=100,height=500,width=500,location=no,toolbar=no,directories=no,menubar=no,status=no');
    return false;
}
function showModal(id){
    $("#"+id).arcticmodal();
}
function changeCaptcha(){
    document.getElementById('captcha-image').src='/main/captcha?id=' + Math.random();
}
function loginIn()
{
    var login = $('#login-in').val();
    var pass = $('#pass-in').val();
    var check = $('#remember').val();
    $.ajax({
        type: 'POST',
        url: '/main',
        dataType: 'html',
        data: {'ajax-query': 'true', 'method': 'GetAuth', 'type-class': 'model', 'login': login, 'pass': pass, 'check': check},
        beforeSend: function(){
            $('#ajax-login-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
        },
        success: function(data){
            if (data == "auth-true") {
                if ('/' === document.location.pathname) {
                    document.location = '/profile';
                } else {
                    document.location.reload();
                }
            } else {
                $("#ajax").remove();
                $('#ajax-login-result').append(data);
            }
        }
    });
}
function regComplete(){
    var pass = $('#pass-reg').val();
    var passConf = $('#pass-reg-confirm').val();
    var codeReg = $('#code-reg').val();
    $.ajax({
        type: 'POST',
        url: document.location.href,
        dataType: 'html',
        data: {'ajax-query': 'true', 'method': 'RegistrationUser', 'type-class': 'model', 'pass': pass, 'pass-conf': passConf, 'code-reg': codeReg},
        beforeSend: function(){
            $('#ajax-modal-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
        },
        success: function(data){
            if(data == "auth-true"){
                document.location = '/profile';
            }else{
                $("#ajax").remove();
                $('#ajax-modal-result').append(data);
            }
        }
    });
}
function closeModalAll(){
    $.arcticmodal("close");
}

$(document).ready(function () {

    $("#autocomplete-ajax").keypress(function(e) {
        if(e.which == 13) {
            var search = $("#autocomplete-ajax").val();
            if(search != '')
                document.location.href = "/search/?s="+search;
        }
    });
    $('#registration').click(function(){ sendSmsRegistration();})
    $('#change-pass-restore').click(function(){ changePassRestore();})


    function changePassRestore(){
        var pass = $('#pass-reg').val();
        var passConf = $('#pass-reg-confirm').val();
        if (validPass() && validConfirmPass()) {
            $.ajax({
                type: 'POST',
                url: document.location.href,
                dataType: 'html',
                data: {'ajax-query': 'true', 'method': 'ChangePassRestore', 'type-class': 'model', 'pass': pass, 'pass-conf': passConf},
                beforeSend: function(){
                    $('#ajax-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
                },
                success: function(data){
                    if(data == "auth-true"){
                        document.location = '/profile';
                    }else{
                        $("#ajax").remove();
                        $('#ajax-result').append(data);
                    }

                }
            });
        }
    }




    function sendSmsRegistration(){
        var phone = $("#phone-reg").val();
        if (validPhone('login-reg-result', 'phone-reg') && validPass() && validConfirmPass()){
            $.ajax({
                type: 'POST',
                url: document.location.href,
                dataType: 'html',
                data: {'ajax-query': 'true', 'type-class':'model', 'method': 'SendSmsRegistration', 'phone': phone},
                beforeSend: function(){
                    $('#ajax-result').removeClass().html('<img id="ajax" src="/skins/img/ajax.gif">');
                },
                success: function(data){
                    $("#ajax").remove();
                    exsistPhone = data.split("#");
                    if(exsistPhone[1] == 1){
                        $("#ajax-result").html("<b style='color: red'>Извините, но текущий телефон уже зарегистрирован в нашей базе.");
                    }else{
                        $('#modal-registration').arcticmodal();
                    }
                }
            });
        }
        return false;
    }

    $('#name_game').change(function () {
        var param = $('#name_game').val();
        selectGameBase(param);
    });
    $('#pass-reg').keyup(validPass).focusout(validPass).bind('paste', validPass);
    $('#pass-reg-confirm').keyup(validConfirmPass).focusout(validConfirmPass).bind('paste', validConfirmPass);
    $('#phone-reg').keyup(function(){validPhone('login-reg-result', 'phone-reg')}).focusout(function(){validPhone('login-reg-result', 'phone-reg')});
    $('#reg_email').keyup(validEmail).focusout(validEmail);


    function validPass() {
        var isValid = false;
        var password = $('#pass-reg').val();
        var strength = checkStrength(password);
        if (password == '' || strength == 0)
            $('#pass-reg-result').removeClass().addClass('valid-false');
        else{
            $('#pass-reg-result').removeClass().addClass('valid-true');
            isValid = true;
        }
        validConfirmPass();
        return isValid;
    }

    function validConfirmPass() {
        var isValid = false;
        var password1 = $('#pass-reg').val();
        var password2 = $('#pass-reg-confirm').val();
        var strength = checkStrength(password1);
        if (password2 == '' || strength == 0 || password1 != password2)
            $('#pass-confirm-reg-result').removeClass().addClass('valid-false');
        else {
            $('#pass-confirm-reg-result').removeClass().addClass('valid-true');
            isValid = true;
        }
        return isValid;
    }
    function validPhone(result, phone){
        var isValid = false;
        $('#'+result).html("");
        var phone = $('#'+phone).val();
        //var phoneReg = /^[+]{0,1}[7-8]{1}[0-9]{10}$/;
        var phoneReg = /^\+?([87](?!95[4-79]|99[^2457]|907|94[^0]|336|986)([348]\d|9[0-689]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55119\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[457]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[34569]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9})$/;
        if(phone.match(phoneReg)) {
            $('#'+result).removeClass().addClass('valid-true');
            isValid = true;
        }else{
            $('#'+result).removeClass().addClass('valid-false');
        }
        return isValid;
    }

    function validEmail(result, email) {
        var isValid = false;
        var email = $('#'+email).val();
        var emailReg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        var validString;
        if (email == '' || !email.match(emailReg))
            $('#'+result).removeClass().addClass('valid-false');
        else {
            $('#'+result).removeClass().addClass('valid-true');
            isValid = true;
        }
        $('#'+result).html(validString);
        return isValid;
    }

    function checkStrength(password) {
        var strength = 0
        if (password.length < 7)
            return strength;
        if (password.length > 7)
            strength += 1;
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
            strength += 1;
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
            strength += 1;
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1;
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1;
        return strength;
    }
});

// Переключатель
$.fn.toggleClick = function(){
    var methods = arguments,
        count = methods.length;
    return this.each(function(i, item){
        var index = 0;
        $(item).click(function(){
            return methods[index++ % count].apply(this,arguments);
        });
    });
};




$(function () {

    // РАБОТА С АВТОКОМПЛИТОМ (OLD)
    'use strict';
    $.ajax({
        url: '/storage/suggestion/suggestion.json',
        dataType: 'json'
    }).done(function (source) {
        var txtArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
            txt      = $.map(source, function (value) { return value; });

            $('#autocomplete-ajax').autocomplete({
                lookup: txtArray,
                lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
                    var re = new RegExp('' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                    return re.test(suggestion.value);
                },
                onHint: function (hint) {
                    $('#autocomplete-ajax-x').val(hint);
                }
            });
        });

});



$(function () {
    /*$('body').on( "customSelect", "select.styled", function() {

    });*/
    $('select.styled').customSelect();
});
(function(a){a.tiny=a.tiny||{};a.tiny.scrollbar={options:{axis:"y",wheel:40,scroll:true,lockscroll:true,size:"auto",sizethumb:"auto",invertscroll:false}};a.fn.tinyscrollbar=function(d){var c=a.extend({},a.tiny.scrollbar.options,d);this.each(function(){a(this).data("tsb",new b(a(this),c))});return this};a.fn.tinyscrollbar_update=function(c){return a(this).data("tsb").update(c)};function b(q,g){var k=this,t=q,j={obj:a(".viewport",q)},h={obj:a(".overview",q)},d={obj:a(".scrollbar",q)},m={obj:a(".track",d.obj)},p={obj:a(".thumb",d.obj)},l=g.axis==="x",n=l?"left":"top",v=l?"Width":"Height",r=0,y={start:0,now:0},o={},e="ontouchstart" in document.documentElement;function c(){k.update();s();return k}this.update=function(z){j[g.axis]=j.obj[0]["offset"+v];h[g.axis]=h.obj[0]["scroll"+v];h.ratio=j[g.axis]/h[g.axis];d.obj.toggleClass("disable",h.ratio>=1);m[g.axis]=g.size==="auto"?j[g.axis]:g.size;p[g.axis]=Math.min(m[g.axis],Math.max(0,(g.sizethumb==="auto"?(m[g.axis]*h.ratio):g.sizethumb)));d.ratio=g.sizethumb==="auto"?(h[g.axis]/m[g.axis]):(h[g.axis]-j[g.axis])/(m[g.axis]-p[g.axis]);r=(z==="relative"&&h.ratio<=1)?Math.min((h[g.axis]-j[g.axis]),Math.max(0,r)):0;r=(z==="bottom"&&h.ratio<=1)?(h[g.axis]-j[g.axis]):isNaN(parseInt(z,10))?r:parseInt(z,10);w()};function w(){var z=v.toLowerCase();p.obj.css(n,r/d.ratio);h.obj.css(n,-r);o.start=p.obj.offset()[n];d.obj.css(z,m[g.axis]);m.obj.css(z,m[g.axis]);p.obj.css(z,p[g.axis])}function s(){if(!e){p.obj.bind("mousedown",i);m.obj.bind("mouseup",u)}else{j.obj[0].ontouchstart=function(z){if(1===z.touches.length){i(z.touches[0]);z.stopPropagation()}}}if(g.scroll&&window.addEventListener){t[0].addEventListener("DOMMouseScroll",x,false);t[0].addEventListener("mousewheel",x,false);t[0].addEventListener("MozMousePixelScroll",function(z){z.preventDefault()},false)}else{if(g.scroll){t[0].onmousewheel=x}}}function i(A){a("body").addClass("noSelect");var z=parseInt(p.obj.css(n),10);o.start=l?A.pageX:A.pageY;y.start=z=="auto"?0:z;if(!e){a(document).bind("mousemove",u);a(document).bind("mouseup",f);p.obj.bind("mouseup",f)}else{document.ontouchmove=function(B){B.preventDefault();u(B.touches[0])};document.ontouchend=f}}function x(B){if(h.ratio<1){var A=B||window.event,z=A.wheelDelta?A.wheelDelta/120:-A.detail/3;r-=z*g.wheel;r=Math.min((h[g.axis]-j[g.axis]),Math.max(0,r));p.obj.css(n,r/d.ratio);h.obj.css(n,-r);if(g.lockscroll||(r!==(h[g.axis]-j[g.axis])&&r!==0)){A=a.event.fix(A);A.preventDefault()}}}function u(z){if(h.ratio<1){if(g.invertscroll&&e){y.now=Math.min((m[g.axis]-p[g.axis]),Math.max(0,(y.start+(o.start-(l?z.pageX:z.pageY)))))}else{y.now=Math.min((m[g.axis]-p[g.axis]),Math.max(0,(y.start+((l?z.pageX:z.pageY)-o.start))))}r=y.now*d.ratio;h.obj.css(n,-r);p.obj.css(n,y.now)}}function f(){a("body").removeClass("noSelect");a(document).unbind("mousemove",u);a(document).unbind("mouseup",f);p.obj.unbind("mouseup",f);document.ontouchmove=document.ontouchend=null}return c()}}(jQuery));