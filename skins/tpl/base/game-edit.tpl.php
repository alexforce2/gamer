<script type="text/javascript" src="/skins/js/validation.js"></script>
<style type="text/css">
    .b-validation .tooltip {
        display: none;
        z-index: 10;
        padding: 5px;
        line-height: 25px;

        position: absolute;
        color: #111;
        border: 1px solid #DCA;
        background: #FEFFD6;

        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-box-shadow: 5px 5px 8px #CCC;
        -webkit-box-shadow: 5px 5px 8px #CCC;

        width: 200px;
        z-index: 99;
        color: #FFFFFF;
        min-height: 25px;
        min-width: 200px;
    }

    .b-validation .error {
        display: block;
    }

    .b-validation .tooltip {
        position: relative;
        background: #1ABC9C;
        border: 4px solid #2C3E50;
    }

    .b-validation .tooltip:after, .b-validation .tooltip:before {
        right: 100%;
        top: 50%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .b-validation .tooltip:after {
        border-color: rgba(26, 188, 156, 0);
        border-right-color: #1ABC9C;
        border-width: 10px;
        margin-top: -10px;
    }

    .b-validation .tooltip:before {
        border-color: rgba(44, 62, 80, 0);
        border-right-color: #2C3E50;
        border-width: 16px;
        margin-top: -16px;
    }
</style>
<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
    <table>
        <tr>
            <td style="vertical-align: top;">
                <div id="content" style="width: 668px;">

                    <div id="content-profile">

                        <div class='left'>

                            <div class="game-edit-form">
                                <h2><?=$data['game']?> Редактирование</h2>

                                <form action="/base/savechanges" method="post">
                                    Уровень сложности: <select class="styled" style="width: 200px" id="level-id" name="level-id">
                                        <?php
                                        foreach($data['levelsArray'] as $level){
                                            if($level['name']==$data['level']){ ?>
                                                <option value="<?=$level['id']?>" selected="selected"><?=$level['name']?></option>
                                            <?php }else{ ?>
                                                <option value="<?=$level['id']?>"><?=$level['name']?></option>
                                            <? }
                                        }
                                        ?>
                                    </select><br><br>
                                    Качество прохождения: <select class="styled" style="width: 300px" id="game-passing" name="game-passing">
                                        <?php
                                        foreach($data['typesCompletedGameArray'] as $type){
                                            if($type['name']==$data['type_complete_game']){ ?>
                                                <option value="<?=$type['id']?>" selected="selected"><?=$type['name']?></option>
                                            <?php }else{ ?>
                                                <option value="<?=$type['id']?>"><?=$type['name']?></option>
                                            <? }
                                        }
                                        ?>
                                    </select><br><br>
                                    Количество квестов: <input id="quest-count" type="text" style="width:50px; margin-right: 40px; <?=(!$data['num_quest'])?"visibility: hidden;":""?> "
                                        value="<?=($data['num_quest']==false)?'Null':$data['num_quest']?>" name="quest-qount" />
                                    <label class="checkbox"><input type="checkbox" id="not-quest-count" <?=(!$data['num_quest'])?"checked":""?>/> Не помню </label>
                                    <div style="float: right; margin: 0px -100px 0px 0px;" class="b-validation">
                                        <div class="tooltip" id="game-quest"></div>
                                    </div><br><br>
                                    Начал играть: <input id="game-start-date" type="text"
                                                         value="<?=(!$data['start_date'])?'Не помню':date('d-m-Y', $data['start_date'])?>"
                                                         onfocus="this.select();_Calendar.lcs(this)"
                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly" name="game-start"/>
                                    <label class="checkbox">
                                        <input type="checkbox" id="game-not-start-date" value="start-date" class="disable-date" <?=(!$data['start_date'])?"checked":""?>/>Не помню
                                    </label>
                                    <div style="float: right; margin: 0px -100px 0px 0px;" class="b-validation">
                                        <div class="tooltip" id="game-start" style="margin-left: 28px;"></div>
                                    </div><br><br>
                                    Закончил играть: <input id="game-end-date" type="text"
                                                            value="<?=(!$data['end_date'])?'Не помню':date('d-m-Y', $data['end_date'])?>"
                                                            onfocus="this.select();_Calendar.lcs(this)"
                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly" name="game-end"/>
                                    <label class="checkbox">
                                        <input type="checkbox" id="game-not-end-date" class="disable-date" value="end-date" <?=(!$data['end_date'])?"checked":""?>/>Не помню
                                    </label>
                                    <div style="float: right; margin: 0px -100px 0px 0px;" class="b-validation">
                                        <div class="tooltip" id="game-end" style="margin-left: 28px;"></div>
                                    </div><br><br>
                                    Отзыв: <br><textarea style="width: 300px; height: 50px" type="text" id="game-description" class="input-txt-profile" data-type="validation" name="game-description"><?=($data['about_game'])?$data['about_game']:'Опишите свои впечатления об игре.'?></textarea>
                                    <div style="float: right; margin: 5px -100px 0px 0px;" class="b-validation">
                                        <div class="tooltip" id="description" style="margin-left: 28px;"></div>
                                    </div><br><br>
                                    <input type="hidden" value="<?=$data['id_game']?>" id="game-id" name="game-id"/>
                                    <!--<div>
                                        <a href="javascript: void(0)" class="btn-login" id="update-completed-game">Сохранить изменения</a>
                                    </div>-->

                                    <!--Подключение загрузчика изображений-->
                                    <div id="edit-main-game">
                                        <div id="img-upload-btn" class="container upload">
                                            <span class="btn">Изображение</span>
                                            <input id="source_img" type="file" name="source_img" multiple />
                                        </div>
                                        <?php
                                        if(count($data['userGameImg']) > 0){
                                            foreach($data['userGameImg'] as $imgSrc){ ?>
                                                <div class="edit-image" style="width: 200px;">
                                                    <img src="<?=$imgSrc['game_img_s']?>">
                                                    <input type="hidden" name="saved-img[]" value="<?=$imgSrc['id']."$".$imgSrc['game_img_s']?>">
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div><br><br>
                                    <!--Конец загрузчика изображений-->

                                    <input type="image" value="Сохранить изменения" class="btn-login" style=""/>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<? include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/main-modal.block.tpl.php"; ?>

<script type="text/javascript">
    var config = {
        form: "edit-main-game",
        dragArea: "dragAndDropFiles",
        visualProgress: "modal",
        img: true,
        uploadUrl: document.location.href,
        method: "UploadUserGameImg",
        limit: 40,
        multi: true
    }

    $(document).ready(function(){
        initMultiUploader(config);
    });
</script>

<style type="text/css">
    #reload-avatar {
        cursor: pointer
    }

    .action-photo-profile {
        background-color: #cac3b7;
        position: absolute;
        width: 282px;
        bottom: 5px;
        opacity: 0.5;
    }

    .action-photo-profile a {
        color: #828b8c;
        font-size: 11px;
        text-decoration: none;
        margin-left: 10px;
    }

    .action-photo-profile img {
        padding-right: 5px;
    }

    .avatar-profile {
        position: relative
    }

    .none-avatar {
        position: relative;
        height: 322px;
        padding-bottom: 5px;
    }

    .none-avatar img {
        width: 282px;
        height: 322px;
    }

    .none-avatar div {
        z-index: 10;
        position: relative;
        bottom: 60px;
        left: 40px;
        color: white;
        text-decoration: underline;
    }

    img.avatar-profile {
        width: 282px !important;
    }

    span.customSelect {
        font: 12px sans-serif;
        background: #fff url(/skins/img/interface/2u7rpec.png) right center no-repeat;
        border: 1px solid #ddd;
        color: #555;
        padding: 7px 9px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px 2px;
    }

    .input-txt-profile {
        width: 256px;
    }

    .area-txt-profile {
        width: 256px;
    }

    span.customSelect.changed {
        background-color: #f0dea4;
    }

        /* начало ранги*/
    .rank-header {
        margin-top: -1px;
        width: 200px;
        border: 1px solid #48596A;
        background-color: #718CA7;
        padding: 5px;
        color: #fff;
    }

    .rank-header i {
        margin-left: 5px;
        font-size: 14px;
    }

    .rank-img-div {
        width: 100%;
        text-align: center;
    }

    .rank-img-div img {
        padding: 25px 0
    }

        /* конец ранги*/

    .block-profile-info-wrapper {
        padding: 3px 0px;
        margin-left: 40px;
        line-height: 1.1em;
    }

    .block-profile-info-header {
        color: #777
    }

    .block-profile-info-txt {
        margin-left: 220px;
        color: #354050;
        width: 300px;
    }

    .modal-gamer-data-td {
        width: 150px;
    }

    .modal-gamer-data-table select {
        width: 200px;
    }

    .info-ajax-modal {
        background: #ffffff;
        font-weight: bold;
        text-align: center;
        padding: 20px 30px 20px;
        -moz-box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        -webkit-box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        -box-shadow: 0 0 80px rgba(0, 0, 0, .4);
        border: 1px solid #8e8e8e;
        color: #354050;
        font-size: 15px;
    }

    .menu_link_prof {
        color: #404040;
    }

    .menu_link_prof:hover {
        color: #48596A;
    }

    .menu_proff {
        text-align: center;
        width: 20px;
        padding: 0px;

        border: 1px solid #d2d2d2;
    }

    .menu_proff1 {
        width: 165px;
        padding: 5px;

        border: 1px solid #d2d2d2;
    }

    .oneShoTmy4 {
        width: 150px;
        padding: 5px;
        border: 1px solid #d2d2d2;

    }

    .oneShoTmy5 {
        text-align: center;
        width: 20px;
        padding: 5px;
        border: 1px solid #d2d2d2;
    }

    #profile-edit-form td {
        padding: 5px;
    }

    .balance-num {
        position: relative;
        bottom: 9px;
        font-weight: bold;
        font-size: 13px;
        text-align: center;
    }

    .age-profile {
        font-family: "age";
        font-size: 20px;
    }

    .age-txt {
        position: relative;
        bottom: 25px;
        right: 1px;
        font-weight: bold;
        font-size: 12px;
        text-align: center;
    }
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
    .edit-image{position: relative;}
    #error-img{padding: 20px; float: right}
    #delete-images{background-image:url(/skins/img/interface/delete-image-hover.png); display: none; opacity: 0.6; width: 15px; height: 15px; position: absolute;  cursor: pointer;}
</style>