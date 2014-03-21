<script type="text/javascript" src="/skins/js/validation.js"></script>
<style type="text/css">
    .b-validation .tooltip {
        display: none;
        z-index:10;
        padding: 5px;
        line-height:16px;

        position:absolute;
        color:#111;
        border:1px solid #DCA;
        background:#FEFFD6;

        border-radius:4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-box-shadow: 5px 5px 8px #CCC;
        -webkit-box-shadow: 5px 5px 8px #CCC;

        width: 200px;
        z-index: 99;
        color:#FFFFFF;
        min-height: 32px;
        min-width: 200px;
    }

    .b-validation .error {
        display: block;
    }

    .b-validation .tooltip { position: relative; background: #1ABC9C; border: 4px solid #2C3E50; }
    .b-validation .tooltip:after, .b-validation .tooltip:before { right: 100%; top: 50%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
    .b-validation .tooltip:after { border-color: rgba(26, 188, 156, 0); border-right-color: #1ABC9C; border-width: 10px; margin-top: -10px; }
    .b-validation .tooltip:before { border-color: rgba(44, 62, 80, 0); border-right-color: #2C3E50; border-width: 16px; margin-top: -16px; }
</style>

<script>
    $(document).ready(function(){
        birthday();
        $("#edit-age-month").change(function(){birthdayActualDate();});
        $("#edit-age-year").change(function(){birthdayActualDate();});
    })

    $(function(){

        $('.hints').poshytip({
            className: 'tip-black',
            showOn: 'focus',
            alignTo: 'target',
            alignX: 'right',
            alignY: 'center',
            offsetX: 1,
            offsetY: 5,
            showTimeout: 100
        });


        $('#city').autocomplete({
            serviceUrl: document.location.href,
            zIndex: 99999, // z-index списка
            type: 'POST',
            params: {
                'ajax-query': 'true',
                'type-class':'model',
                'method': 'GetCities',
                'limit': '10'
            },
            dataType: 'html',
            deferRequestBy: 200,
        });

        // ВАЛИДАЦИЯ
        // $('.btn-login, input, textarea, div').click(function(){
        $('.btn-login').click(function(){
            if (!_validation()) {
                return false;
            }
        })

    });

    function editGamerData(){
        var gameExperience = $("#game-experience").val();
        var loveGenre      = $("#love-genre").val();
        var loveComplexity = $("#love-complexity").val();
        var loveGame       = $("#love-game").val();

        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method':'MainEditGamerData', 'game-experience': gameExperience, 'love-genre': loveGenre, 'love-complexity': loveComplexity, 'love-game': loveGame},
            beforeSend: function(){
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function(data){
                location.reload();
            }
        });
    }

    // РЕДАКТИРОВАНИЕ ДАННЫХ ПОЛЬЗОВАТЕЛЯ
    function editUserOtherData(){
        var nick    = $("#nick").val();
        var skype    = $("#skype").val();
        var icq    = $("#icq").val();
        var steam    = $("#steam").val();
        var city    = $("#city").val();
        var aboutMe = $("#about-me").val();

        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method':'MainEditUserOtherData', 'nick': nick, 'skype': skype, 'icq': icq, 'steam': steam, 'city': city, 'about-me': aboutMe},
            beforeSend: function(){
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function(data){
                data = $.parseJSON(data);
                if ( data.city_success == true ) {
                    $('.tooltip#city').removeClass('error')

                     location.reload();
                } else {
                    $('.tooltip#city').addClass('error').html('В нашей базе нет такого населённого пункта')
                    return false;
                }
            }
        });
    }

    //Настрой аккаунта
    function editAccountData() {
        var oldPassword = $("#oldPassword").val(),
            newPassword = $("#newPassword").val(),
            newEmail    = $("#newEmail").val(),
            newPhone    = $("#newPhone").val();

        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {
                'ajax-query': 'true',
                'type-class': 'model',
                'method':'MainEditAccountData',
                'oldPassword': oldPassword,
                'newPassword': newPassword,
                'newEmail': newEmail,
                'newPhone': newPhone
            },
            beforeSend: function(){
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function(data){
                location.reload();
            }
        });
    }


    function editUserData(){
        var firstName  = $("#edit-first-name").val();
        var lastName   = $("#edit-last-name").val();
        var patronymic = $("#edit-patronymic").val();
        var sex        = $("input[name=edit-sex]:checked").val();
        var aboutMe    = $("#edit-about-me").val();
        var day        = $("#edit-age-day").val();
        var month      = $("#edit-age-month").val();
        var year       = $("#edit-age-year").val();
        day   = day.length == 1 ? "0"+  day : day;
        month = month.length == 1 ? "0"+  month : month;
        var birthday = day + "." + month + "."+ year;

        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class': 'model', 'method':'MainEditUserData', 'first-name': firstName, 'last-name': lastName, 'patronymic': patronymic, 'sex': sex, 'about-me': aboutMe, 'birthday': birthday},
            beforeSend: function(){
                $('#send').before('<img id="ajax-img-loader" src="/skins/img/ajax/loader-page.gif">');
            },
            success: function(data){
                $("#ajax-img-loader").remove();
                $('#info-ajax-modal').arcticmodal();
                setTimeout(closeModalAll, 1000);
                location.reload();
            }
        });
    }




    function birthdayActualDate(){
        var month              = parseInt($("#edit-age-month").val());
        var year               = parseInt($("#edit-age-year").val());
        var day                = parseInt($("#edit-age-day").val());
        var dataDay            = '';
        var integerDayForYear  = (year - 1948) / 4;
        var integerDayForMonth = month / 2;
        if(month >= 8)
            var resDay = ((integerDayForMonth + "").indexOf(".") > 0) ? 30 : 31;
        else if(month == 2)
            var resDay = ((integerDayForYear + "").indexOf(".") > 0) ? 28 : 29;
        else
            var resDay = ((integerDayForMonth + "").indexOf(".") > 0) ? 31 : 30;

        for(i = 1; i <= resDay; i++){
            var selected = (day == i) ? 'selected' : '';
            dataDay += '<option value="'+ i +'" '+ selected +' >' + i + '</option>';
        }
        $("#edit-age-day").html('').append(dataDay);
    }

    function birthday(){
        var dataYear = '';
        var dataDay = '';
        for(i = 2005; i >= 1948; i--){
            dataYear += '<option value="'+ i +'">' + i + '</option>';
        }

        for(i = 1; i <= 31; i++){
            dataDay += '<option value="'+ i +'">' + i + '</option>';
        }
        $("#edit-age-year").append(dataYear);
        $("#edit-age-day").append(dataDay);
    }

    function confirmSaveInfo(){
        $("#box-modal-main-save").arcticmodal();
    }




</script>


<h2>Мой профиль</h2>

<? if(!empty($data['user']['first_name']) && !empty($data['user']['last_name']) &&
    !empty($data['user']['patronymic']) && !empty($data['user']['age']) && !empty($data['user']['sex']) && !empty($data['user']['about_me'])) { ?>
    <div id="edit-main-data" class="reveal-modal">
        <h3>Редактирование (общие данные)</h3>
        <p>Имя<input type="text" id="edit-first-name"></p>
        <p>Фамилия<input type="text" id="edit-last-name"></p>
        <p>Отчество<input type="text" id="edit-patronymic"></p>
        <p>Возраст
            <select id="edit-age-day">

            </select>
            <select id="edit-age-month">
                <option value="1">Январь</option>
                <option value="2">Февраль</option>
                <option value="3">Март</option>
                <option value="4">Апрель</option>
                <option value="5">Май</option>
                <option value="6">Июнь</option>
                <option value="7">Июль</option>
                <option value="8">Август</option>
                <option value="9">Сентябрь</option>
                <option value="10">Октябрь</option>
                <option value="11">Ноябрь</option>
                <option value="12">Декабрь</option>
            </select>
            <select id="edit-age-year"></select> </p>
        <p>Пол
            мужской<input type="radio" name="edit-sex" value="1" />
            женский <input type="radio" name="edit-sex" value="2" />
        </p>
        <p><textarea type="text" id="edit-about-me"></textarea></p>

        <p id="send"><a href="javascript:editUserData()">Сохранить</a></p>
    </div>
<?}?>

<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
<table >
<tr>
<td style="vertical-align: top;">
    <div id="content" style="width: 668px;">

        <div id="content-profile">

            <div class='left'>
                <?
                if(!empty($this->user['first_name']) && !empty($this->user['last_name']) &&
                    !empty($this->user['patronymic']) && !empty($this->user['birthday']) && !empty($this->user['sex'])){
                    $nameProfile = $this->user['last_name'] . " " . $this->user['first_name'] . " " . $this->user['patronymic'];
                    ?>
                    <table>
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Общие данные</h4>
                            </td>
                            <td>
                                <div style="position: relative; width: 395px; left: 5px; height: 4px; background:url(/skins/img/interface/point.png) "></div>
                            </td>
                            <td>
                                <div class="right" style="position: relative; font-size: 12px; bottom: 1px; left: 10px;">
                                    <a data-reveal-id="edit-main-data" data-animation="fade" style="cursor: pointer; color:#507fb6; text-decoration: none;" href="javascript:showModal('box-modal-data-user')">Редактировать</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table >
                        <tr>
                            <td style="vertical-align: top;">
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Полное имя:</div>
                                        <div class="block-profile-info-txt"><?=$nameProfile; ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Город</div>
                                        <div class="block-profile-info-txt"><?= $this->GetCity(); ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Возраст</div>
                                        <div class="block-profile-info-txt"><?= $this->GetHappyBirthday(); ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Пол:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['sex'] == 1 ? "Мужской" : "Женский"; ?></div>
                                    </div>
                                <? if(!empty($this->user['about_me'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Немного о себе:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['about_me']; ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Скайп:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['skype']; ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">ICQ:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['icq']; ?></div>
                                    </div>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Steam:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['steam']; ?></div>
                                    </div>
                                <? } ?>
                            </td>
                        </tr>
                    </table>

                    <!-- Модальная форма редактировнаия общих данных -->
                    <div class="hide">
                        <div class="box-modal" id="box-modal-data-gamer" style="width: 390px">
                            <div class="header-modal">
                                <b>Редактирование данных игрока</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>

                            <div style="padding:15px; padding-bottom: 45px;">
                                <table class="modal-gamer-data-table">
                                    <tr>
                                        <td class="modal-gamer-data-td">Игровой стаж на PC:</td>
                                        <td>
                                            <select id="game-experience" class="styled" style="width: 180px; height: 15px;">
                                                <?php
                                                    for ($year = 1; $year <= 20; $year++) {
                                                        if($year == "1")  $str = " год";
                                                        else if($year >= "2" && (string)$year <= "4" && $year > "1") $str = " года";
                                                        else $str = " лет";
                                                        $game_exp = $year .' '. $str;
                                                        ?>

                                                        <?php if ( (int)$game_exp == (int)$this -> GetGameExp() ) { ?>
                                                            <option selected='selected' value="<?= $game_exp ?>"><?= $game_exp ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?= $game_exp ?>"><?= $game_exp ?></option>
                                                        <?php } ?>
                                                <?php } ?>

                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td" >Любимый жанр:</td>
                                        <td>
                                            <select id="love-genre" class="styled" style="width: 180px; height: 15px;">
                                                <?php foreach($data['genre'] as $genre) {
                                                    $name = $genre -> name;
                                                    ?>

                                                    <?php if ( $name == $this -> GetLoveGenre() ) { ?>
                                                        <option selected='selected' value="<?= $name ?>"><?= $name ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $name ?>"><?= $name ?></option>
                                                    <?php } ?>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Предпочтительная сложность:</td>
                                        <td>
                                            <select id="love-complexity" class="styled" style="width: 180px; height: 15px;">
                                                <?php foreach (self::$types_complexity as $type) {?>

                                                    <?php if ( $type == $this -> GetLoveComplexity() ) { ?>
                                                        <option selected='selected' value="<?= $type ?>"><?= $type ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $type ?>"><?= $type ?></option>
                                                    <?php } ?>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Любимая игра:</td>
                                        <td>
                                            <select id="love-game" class="styled" style="width: 180px; height: 15px;">
                                                <?php foreach($data['games'] as $game){
                                                    $name = $game -> name;
                                                    ?>

                                                    <?php if ( $name == $this -> GetLoveGame() ) { ?>
                                                        <option selected='selected' value="<?= $name ?>"><?= $name ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $name ?>"><?= $name ?></option>
                                                    <?php } ?>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table><br>
                                <div style="float: right"><a href="javascript:editGamerData()" class="btn-login">Продолжить</a>
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                            </div>
                        </div>
                    </div>

                    <!-- Модальная форма редактировнаия данных игрока -->
                    <div class="hide">
                        <div class="box-modal" id="box-modal-data-user" style="width: 390px">
                            <div class="header-modal">
                                <b>Редактирование данных пользователя</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>


                            <div style="padding:15px; padding-bottom: 45px;">
                                <table class="modal-gamer-data-table">
                                    <tr>
                                        <td class="modal-gamer-data-td">Ник:</td>
                                        <td>
                                            <input  style="width: 280px" type="text" id="nick" class="input-txt-profile" value="<?=$this -> user['nick'];?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Скайп:</td>
                                        <td>
                                            <input  style="width: 280px" type="text" id="skype" class="input-txt-profile" value="<?=$this -> user['skype'];?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">ICQ:</td>
                                        <td>
                                            <input  style="width: 280px" type="text" id="icq" class="input-txt-profile" value="<?=$this -> user['icq'];?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Steam:</td>
                                        <td>
                                            <input  style="width: 280px" type="text" id="steam" class="input-txt-profile" value="<?=$this -> user['steam'];?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Город:</td>
                                        <td>
                                            <input style="width: 280px" type="text" id="city" class="input-txt-profile" data-type="validation" value="<?= $this -> user['city']; ?>" >
                                            <div style="float: right; margin: -45px -235px 0px 0px;" class="b-validation">
                                                <div class="tooltip " id="city" style="margin-left: 28px;">
                                                </div>
                                            </div>
                                            <div id="selction-ajax"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="modal-gamer-data-td" >О себе:</td>
                                        <td>
                                            <textarea style="width: 280px" type="text" id="about-me" class="input-txt-profile" data-type="validation" ><?= $this -> user['about_me']; ?></textarea>
                                        </td>
                                    </tr>
                                </table><br>
                                <div style="float: right"><a href="javascript:editUserOtherData()" class="btn-login">Продолжить</a>
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                            </div>
                        </div>
                    </div>

                    <table>
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Данные игрока</h4>

                            </td>
                            <td>

                                <div style="position: relative; width: 395px; left: 5px; height: 4px; background:url(/skins/img/interface/point.png) "></div>
                            </td>
                            <td>
                                <div class="right" style="position: relative; font-size: 12px; bottom: 1px; left: 10px;">
                                    <a data-reveal-id="edit-main-data" href="javascript:showModal('box-modal-data-gamer')" data-animation="fade" style="cursor: pointer; text-decoration: none;  color:#507fb6">Редактировать</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td style="vertical-align: top;">
                                <? if(!empty($this->user['game_experience'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Игровой стаж:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['game_experience'];?></div>
                                    </div>
                                <? } if(!empty($this->user['love_genre'])) { ?>

                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Любимый жанр:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['love_genre'];?></div>
                                    </div>
                                <? } if(!empty($this->user['love_complexity'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Предпочитаю сложность:</div>
                                        <div class="block-profile-info-txt" ><?= $this->user['love_complexity'];?></div>
                                    </div>
                                <? } if(!empty($this->user['love_game'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Любимая игра:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['love_game'];?></div>
                                    </div>
                                <? } ?>
                            </td>
                        </tr>
                    </table>
                    <table class="left" style="padding: 0 60px">
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Ранг: <?=$data['rank']['name'];?></h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php if($data['rank']['source_img'] == ""){ ?>
                                    <b style="color:gray">Заслуги отсутствуют.</b>
                                <? }else{ ?>
                                    <img src="/skins/img<?=$data['rank']['source_img'];?>" title="<?=$data['rank']['description'];?>">
                                <? } ?>
                            </td>
                        </tr>
                    </table>
                    <table class="right" style="padding: 0 60px">
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Награды:</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php if($data['award']['source_img'] == ""){ ?>
                                    <b style="color:gray">Заслуги отсутствуют.</b>
                                <? }else{ ?>
                                <img src="/skins/img<?=$data['award']['source_img'];?>" title="<?=$data['award']['description'];?>">
                                <? } ?>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Данные аккаунта</h4>

                            </td>
                            <td>

                                <div style="position: relative; width: 395px; left: 5px; height: 4px; background:url(/skins/img/interface/point.png) "></div>
                            </td>
                            <td>
                                <div class="right" style="position: relative; font-size: 12px; bottom: 1px; left: 10px;">
                                    <a href="javascript:showModal('box-modal-data-account')" data-animation="fade" style="cursor: pointer; text-decoration: none;  color:#507fb6">Редактировать</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td style="vertical-align: top;">
                                <? if(!empty($this->user['email'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Email:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['email'];?></div>
                                    </div>
                                <? } if(!empty($this->user['phone'])) { ?>
                                    <div class="block-profile-info-wrapper">
                                        <div class="block-profile-info-header left">Телефон:</div>
                                        <div class="block-profile-info-txt"><?= $this->user['phone'];?></div>
                                    </div>
                                <? } ?>
                            </td>
                        </tr>
                    </table>

                    <!-- Модальная форма редактировнаия аккаунта -->
                    <div class="hide">
                        <div class="box-modal" id="box-modal-data-account" style="width: 390px">
                            <div class="header-modal">
                                <b>Настройки аккаунта</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>

                            <div style="padding:15px; padding-bottom: 45px;">
                                <table class="modal-gamer-data-table">

                                    <tr>
                                        <td class="modal-gamer-data-td">Текущий пароль:</td>
                                        <td>
                                            <input  style="width: 200px" type="password" id="oldPassword" class="input-txt-profile" value="" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="modal-gamer-data-td">Новый пароль:</td>
                                        <td>
                                            <input  style="width: 200px" type="password" id="newPassword" class="input-txt-profile" value="" >
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="modal-gamer-data-td">EMail:</td>
                                        <td>
                                            <input  style="width: 200px" type="email" id="newEmail" class="input-txt-profile" value="<?=$this->user['email'];?>" >
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="modal-gamer-data-td">Телефон:</td>
                                        <td>
                                            <input  style="width: 200px" type="text" id="newPhone" class="input-txt-profile" value="<?=$this->user['phone'];?>" >
                                        </td>
                                    </tr>

                                </table><br>
                                <div style="float: right">
                                    <a href="javascript:editAccountData()" class="btn-login">Продолжить</a>
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?} else { ?>
                    <div class="hide">
                        <div class="box-modal" id="box-modal-main-save" style="width: 500px">
                            <div class="header-modal">
                                <b>Подтверждение сохранения данных аккаунта</b>
                                <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                    <img src="/skins/img/interface/close-modal.png"></div>
                            </div>
                            <div style="padding:15px; padding-bottom: 45px;">
                                <img id="captcha-image" src="/skins/img/interface/warning-update.png" class="left">
                                <div style="margin-left: 120px;">
                                    <span>Сохраняя <b>основную</b> информацию о себе, вы больше не сможете её изменить, вы уверены что хотите продолжить?</span>
                                </div><br>
                                <div style="float: right"><a href="javascript:editUserData()" class="btn-login">Продолжить</a>
                                    <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                            </div>
                        </div>
                    </div> <!-- Модальная форма подтверждения о внесении данных пользователя единожды -->
                    <b style="font-size: 12px;">
                        Внимание! При заполнении данной формы просьба указывать реальные сведения, в противном случае администрация сайта GS11 имеет право не выплачивать
                        Вам выигрышную сумму. Ваши данные строго конфиденциальны. Более подробную информацию читайте в <a style="color: #507fb6; text-decoration: none" href="/about/offer">правилах сайта.</a>
                    </b>
                    <table>
                        <tr>
                            <td>
                                <h4 style="line-height: 0.8em;" class="page-title FL">Общие данные</h4>
                            </td>
                            <td>
                                <div style="position: relative; width: 395px; left: 5px; height: 4px; background:url(/skins/img/interface/point.png) "></div>
                            </td>
                            <td>
                                <div class="right" style="position: relative; font-size: 12px; bottom: 1px; left: 10px;">
                                    <a data-reveal-id="edit-main-data" data-animation="fade" style="cursor: pointer; color:#507fb6;text-decoration: none"  href="javascript:confirmSaveInfo()">Сохранить</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td style="vertical-align: top;">
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Фамилия:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-last-name" class="input-txt-profile" value="<?=$this->user['last_name'];?>" ></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Имя:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-first-name" class="input-txt-profile" value="<?=$this->user['first_name'];?>"></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Отчество:</div>
                                    <div class="block-profile-info-txt"><input type="text" id="edit-patronymic" class="input-txt-profile" value="<?=$this->user['patronymic'];?>"></div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Возраст</div>
                                    <div class="block-profile-info-txt">
                                        <select id="edit-age-day" class="styled" style="width: 40px;"></select>
                                        <select id="edit-age-month" class="styled" style="width: 100px;">
                                            <option value="1">Январь</option>
                                            <option value="2">Февраль</option>
                                            <option value="3">Март</option>
                                            <option value="4">Апрель</option>
                                            <option value="5">Май</option>
                                            <option value="6">Июнь</option>
                                            <option value="7">Июль</option>
                                            <option value="8">Август</option>
                                            <option value="9">Сентябрь</option>
                                            <option value="10">Октябрь</option>
                                            <option value="11">Ноябрь</option>
                                            <option value="12">Декабрь</option>
                                        </select>
                                        <select id="edit-age-year" class="styled" style="width: 60px;" ></select>
                                    </div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Пол:</div>
                                    <div class="block-profile-info-txt">
									<label class="radio">
										<input type="radio" name="edit-sex" value="1">мужской
									</label>
									<label class="radio" style="margin-left:25px">
										<input type="radio" name="edit-sex" value="2">женский
									</label>

                                    </div>
                                </div>
                                <div class="block-profile-info-wrapper">
                                    <div class="block-profile-info-header left">Немного о себе:</div>
                                    <div class="block-profile-info-txt">
                                        <textarea type="text" id="edit-about-me" class="area-txt-profile"><?=$this->user['about_me'];?></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                <? } ?>

            </div>
        </div>
    </div>
</td>
<td>
    <div style="position: relative; bottom: 55px;">
    <h3 id="nick-txt"><?=$this->user['nick'];?></h3>
    <?php if (empty($this->user['img_avatar'])) { ?>
        <div class="none-avatar">
            <img src="/skins/img/m.jpg"/><br />
            <div class="container upload">
                <span class="btn" style="width: 200px;">Загрузить фотографию</span>
                <input id="file" type="file" value="/profile/upload" name="file[]" style="width: 200px;" />
                <input type="hidden" id="url" value="/profile/upload-avatar">
            </div>
            <div id="info"></div>
        </div>
    <?php } else { ?>
        <div class="avatar-profile">
            <?php if(strlen($this->user['img_avatar']) > 20){?>
                <img style="width: 282px;" src="<?= $this->user['img_avatar']; ?>"/><br />
            <?}else{ ?>
                <img style="width: 282px;" src="/storage<?= $this->user['img_avatar']; ?>.jpg"/><br />
            <?}?>
            <div class="hide action-photo-profile">
               <a href="javascript:showModal('box-modal-delete-img-profile')" ><img src="/skins/img/interface/delete-img.png" />Удалить фотографию</a><br>
                <a id="reload-avatar"><img src="/skins/img/interface/upload-img.png" />Загрузить новую фотографию</a>
                <input id="file" type="file" name="file[]" class="hide" />
                <input type="hidden" id="url" value="/profile/reload-avatar">
            </div>
        </div>
        <div class="hide">
            <div class="box-modal" id="box-modal-delete-img-profile" style="width: 360px">
                <div class="header-modal">
                    <b>Удаление фотографии профиля</b>
                    <div  class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                        <img src="/skins/img/interface/close-modal.png"></div>
                </div>
                <div style="padding:15px; padding-bottom: 45px;">
                    <div>
                        <span>Вы действительно хотите удалить фотографию?</span>
                    </div><br>
                    <div id="ajax-modal-result" style="position: absolute; bottom: 25px; left: 25px;"></div>
                    <div style="float: right"><a href="javascript:deleteImgAvatar()" class="btn-login">Продолжить</a>
                        <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a></div>
                </div>
            </div>
        </div>
    <?}?>

    <table style="width: 282px; margin-top: 20px; border-collapse: collapse">
        <tbody>
        <tr>
            <td class="oneShoTmy4">Пройденных игр:</td>
            <td class="oneShoTmy5"><?=$this->user['complete_games']; ?></td>
        </tr>
        <tr>
            <td class="oneShoTmy4">Количество сообщений:</td>
            <td class="oneShoTmy5"><?=$this->user['count_message']; ?></td>
        </tr>
        <tr>
            <td class="oneShoTmy4">Участие в турнирах:</td>
            <td class="oneShoTmy5"><?=$this->user['count_tournament']; ?></td>
        </tr>
        <tr>
            <td class="oneShoTmy4">Наград:</td>
            <td class="oneShoTmy5"><?=$this->user['count_award']; ?></td>
        </tr>
        </tbody>
    </table><br>

    <table style="border-collapse: collapse; width: 282px; ">
        <tbody>
        <tr>
            <td class="menu_proff">
                <img src="/skins/img/interface/menu-profile.png"  style="position: relative; top:2px;" width="16px" border="0">
            </td>
            <td class="menu_proff1">
                <a href="/profile" class="menu_link_prof"  style="text-decoration:none;">Мой профиль</a>
            </td>
        </tr>
<!--        <tr>-->
<!--            <td class="menu_proff">-->
<!--                <img src="/skins/img/interface/menu-settings.png"  style="position: relative; top:2px;" width="16px" border="0">-->
<!--            </td>-->
<!--            <td class="menu_proff1">-->
<!--                <a href="/profile/setting" class="menu_link_prof"   style="text-decoration:none;">Мои настройки</a>-->
<!--            </td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td class="menu_proff">-->
<!--                <img src="/skins/img/interface/menu-tournament.png"  style="position: relative; top:3px;" width="16px" border="0">-->
<!--            </td>-->
<!--            <td class="menu_proff1"><a href="/profile/tournament"  class="menu_link_prof" style="text-decoration:none;">Мои турниры</a></td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td class="menu_proff">-->
<!--                <img src="/skins/img/interface/menu-statistics.png" style="position: relative; top:2px;" width="16px" border="0">-->
<!--            </td>-->
<!--            <td class="menu_proff1"><a href="/profile/statistic" class="menu_link_prof" style="text-decoration:none;">Моя статистика</a></td>-->
<!--        </tr>-->
        <tr>
            <td class="menu_proff">
                <img src="/skins/img/interface/menu-billing.png"  style="position: relative; top:2px;" width="16px" border="0">
            </td>
            <td class="menu_proff1">
                <a href="/billing" class="menu_link_prof"   style="text-decoration:none;">Управление платежами</a>
            </td>
        </tr>
        <tr>
            <td class="menu_proff">
                <img src="/skins/img/interface/menu-billing.png"  style="position: relative; top:2px;" width="16px" border="0">
            </td>
            <td class="menu_proff1">
                <a href="/profile/messages" class="menu_link_prof"   style="text-decoration:none;">Сообщения<?=(!empty($_SESSION['user-data']['unread_notify']))?'<sup style="color: red; font-weight: bold">'.$_SESSION['user-data']['unread_notify'].'</span>':''?></a>
            </td>
        </tr>
        <tr>
            <td class="menu_proff">
                <img src="/skins/img/interface/menu-billing.png"  style="position: relative; top:2px;" width="16px" border="0">
            </td>
            <td class="menu_proff1">
                <a href="/profile/notify-setting" class="menu_link_prof"   style="text-decoration:none;">Настройка уведомлений</a>
            </td>
        </tr>
        </tbody>
    </table>
        </div>
</td>
</tr>
</table>


</div>

<div class="hide">
    <div class="box-modal" id="upload-process-ajax-modal" style="width: 190px">
        <div class="info-ajax-modal" id="upload-process">

        </div>
    </div>
</div>
<div class="hide">
    <div class="box-modal" id="info-ajax-modal" style="width: 390px">
        <div class="info-ajax-modal">
            Данные игрока успешно сохранены
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){


        $(".avatar-profile").hover(function(){
            $(".action-photo-profile").slideDown("fast")
        }).mouseleave(function(){
            $(".action-photo-profile").slideUp("fast");
        });

        $('#file').bind('change', function () {

            urlPath = $('#url').val();
            serviceGS11.uploadAvatar(urlPath);
        })
        $('#reload-avatar').bind('click', function(e) {
            $('#file').click();
        });

    })




    function deleteImgAvatar(){
        $.ajax({
            type: 'POST',
            url: "/profile/delete-avatar",
            dataType: 'html',
            beforeSend: function(){
                $('#ajax-modal-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
            },
            success: function(data){
                $("#ajax").remove();
                closeModalAll();
                $('.avatar-profile').remove();
                $('#nick-txt').after('<div class="none-avatar"><img src="/skins/img/m.jpg"/><br /><div class="container upload">' +
                    '<span class="btn" style="width: 200px;">Загрузить фотографию</span>' +
                    '<input id="file" type="file" value="/profile/upload" name="file[]" style="width: 200px;" />' +
                    '<input type="hidden" id="url" value="/profile/upload-avatar"></div><div id="info"></div></div>');
                $('#file').bind('change', function () {
                    urlPath = $('#url').val();
                    serviceGS11.uploadAvatar(urlPath);
                })

            }
        });
    }
</script>


<style type="text/css">
    #reload-avatar{cursor: pointer}
    .action-photo-profile{ background-color: #cac3b7; position: absolute;  width: 282px; bottom: 5px; opacity: 0.5;}
    .action-photo-profile a{color: #828b8c; font-size: 11px; text-decoration: none; margin-left: 10px;}
    .action-photo-profile img{padding-right: 5px; }
    .avatar-profile{position: relative}
    .none-avatar{position: relative; height: 322px; padding-bottom: 5px;}
    .none-avatar img{width: 282px; height: 322px;}
    .none-avatar div{z-index: 10; position: relative; bottom: 60px; left: 40px; color: white; text-decoration: underline;}
    img.avatar-profile{width: 282px !important;}
    span.customSelect {
        font:12px sans-serif;
        background:#fff url(/skins/img/interface/2u7rpec.png) right center no-repeat;
        border:1px solid #ddd;
        color:#555;
        padding:7px 9px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px 2px;
    }

    .input-txt-profile{width: 256px;}
    .area-txt-profile{width: 256px;}

    span.customSelect.changed {
        background-color: #f0dea4;
    }
        /* начало ранги*/
    .rank-header{margin-top:-1px; width: 200px; border:1px solid #48596A; background-color:#718CA7; padding:5px; color: #fff;}
    .rank-header i{margin-left: 5px; font-size: 14px;}
    .rank-img-div{width: 100%; text-align: center;}
    .rank-img-div img{padding: 25px 0}
        /* конец ранги*/


    .block-profile-info-wrapper{padding: 3px 0px; margin-left: 40px; line-height: 1.1em;}
    .block-profile-info-header{color: #777}
    .block-profile-info-txt{margin-left: 220px; color: #354050; width: 300px;}
    .modal-gamer-data-td{width: 150px;}
    .modal-gamer-data-table select{width: 200px;}

    .info-ajax-modal {  background: #ffffff; font-weight: bold; text-align: center; padding: 20px 30px 20px; -moz-box-shadow: 0 0 80px rgba(0,0,0,.4); -webkit-box-shadow: 0 0 80px rgba(0,0,0,.4); -box-shadow: 0 0 80px rgba(0,0,0,.4); border: 1px solid #8e8e8e; color: #354050; font-size: 15px;}






    .menu_link_prof{color:#404040;}
    .menu_link_prof:hover{color:#48596A;}
    .menu_proff{
        text-align:center;
        width:20px;
        padding:0px;

        border:1px solid #d2d2d2;
    }
    .menu_proff1{
        width:165px;
        padding:5px;

        border:1px solid #d2d2d2;
    }


    .oneShoTmy4{
        width:150px;
        padding:5px;
        border:1px solid #d2d2d2;

    }
    .oneShoTmy5{
        text-align:center;
        width:20px;
        padding:5px;
        border:1px solid #d2d2d2;
    }
    #profile-edit-form td{padding:5px;}
    .balance-num{position: relative; bottom: 9px; font-weight: bold; font-size: 13px; text-align: center;}
    .age-profile{font-family: "age"; font-size: 20px;}
    .age-txt{position: relative; bottom: 25px; right: 1px; font-weight: bold; font-size: 12px; text-align: center;}
</style>