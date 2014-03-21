<script type="text/javascript" src="/skins/js/validation.js"></script>



<h2>Пройденные игры</h2>
<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
    <table>
        <tr>
            <td style="vertical-align: top;">
                <div id="content" style="width: 668px;">

                    <div id="content-profile">

                        <div class='left'>

                            <!-- Форма добавления пройденных игр Добавить пройденную игру<br>-->
                            <?php
                            $idSession = (int)$_SESSION['user-data']['id'];
                            $idCheck = (int)$data['check-user-id'];
                            if($idSession === $idCheck) {
                            ?>
                            <div>
                                <a data-reveal-id="edit-main-data" href="javascript:showModal('box-modal-data-gamer')"
                                   data-animation="fade" style="cursor: pointer; text-decoration: none;  color:#507fb6">Добавить
                                    пройденную игру</a>
                                <br><a href="/base/users/">Пользователи</a>
                            </div>
                            <?php } ?>
                            <!-- таблица с пройденными играми пользователя здесь таблица с пройденными играми пользователя<br>-->
                            <?php
                            foreach ($data['user-completed-games'] as $arrayGame) {?>
                                <div class="game-wrap" style="border: 1px solid #1abc9c; margin: 10px; padding: 10px">
                                    <div class="game-head">
                                        <h4><img src="/storage<?= $arrayGame['source_img_s'] ?>"><?= $arrayGame['game'] ?> жанр: <?= $arrayGame['genre'] ?> posted: <?= date("d.m.Y", $arrayGame['post_date']) ?><h4>
                                    </div>
                                    <div class="game-desc">
                                        <p>
                                            <?= $arrayGame['about_game'] ?>
                                        </p>
                                    </div>
                                    <div class="game-v-ch" style="font: italic bold small serif">
                                        <a href='<?= "/base/view/$arrayGame[id]?iduser={$data['user-completed-games'][0]['id_user']}" ?>'>Читать подробнее</a>
                                        <?php
                                        if($idSession === $idCheck) {
                                        ?>
                                        <a href='<?= "/base/edit/$arrayGame[id]" ?>' style="float: right">Редактировать</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php }
                            ?>

                            <!-- Модальная форма добавления пройденной игры -->
                            <div class="hide">
                                <div class="box-modal" id="box-modal-data-gamer" style="width: 500px">
                                    <div class="header-modal">
                                        <b>Добавление пройденной игры</b>

                                        <div class="box-modal_close arcticmodal-close" onclick="closeModalAll()">
                                            <img src="/skins/img/interface/close-modal.png">
                                        </div>
                                    </div>
                                    <div style="padding:15px; padding-bottom: 45px;">
                                        <table class="modal-gamer-data-table">
                                            <tr>
                                                <td class="modal-gamer-data-td">Игра:</td>
                                                <td>
                                                    <input style="width: 188px" type="text" id="game" class="input-txt-profile" data-type="validation" placeholder="Пройденная игра">
                                                    <div style="float: right; margin: -2px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game" style="margin-left: 28px;"></div>
                                                    </div>
                                                    <div id="selction-ajax"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Уровень сложности:</td>
                                                <td id="game-level-parent">
                                                    <select id="game-level" name="game-level" class="styled" style="width: 200px; height: 15px;">
                                                        <option selected='selected' value="null">Выбрать уровень</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Качество прохождения:</td>
                                                <td>
                                                    <select style="width: 200px; height: 15px;" id="game-passing">
                                                        <?php
                                                        foreach($data["type-complete-game"] as $type){
                                                            echo "<option value='$type[id]'>$type[name]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Количество квестов:</td>
                                                <td>
                                                    <input id="quest-count" type="text" style="width:50px; margin-right: 40px"/>
                                                    <label class="checkbox"><input type="checkbox" id="not-quest-count"/> Не помню </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-quest" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Начал играть:</td>
                                                <td>
                                                    <input id="game-start-date" type="text" value="дд-мм-гггг" onfocus="this.select();_Calendar.lcs(this)"
                                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly"/>
                                                    <label class="checkbox">
                                                        <input type="checkbox" id="game-not-start-date" value="start-date" class="disable-date"/>Не помню
                                                    </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-start" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Закончил играть:</td>
                                                <td>
                                                    <input id="game-end-date" type="text" value="дд-мм-гггг" onfocus="this.select();_Calendar.lcs(this)"
                                                           onclick="event.cancelBubble=true;this.select();_Calendar.lcs(this)" style="width: 90px" readonly="readonly"/>
                                                    <label class="checkbox">
                                                        <input type="checkbox" id="game-not-end-date" class="disable-date" value="end-date"/>Не помню
                                                    </label>
                                                    <div style="float: right; margin: 0px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-end" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Проголосуйте:</td>
                                                <td id="game-rating-parent">
                                                    <div class="rating">
                                                        <input type="hidden" class="val" value="5"/>
                                                        <input type="hidden" class="votes" value="0"/>
                                                    </div>
                                                    <div style="float: right; margin: -52px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="game-rating" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="modal-gamer-data-td">Отзыв:</td>
                                                <td>
                                                    <textarea style="width: 188px" type="text" id="game-description" class="input-txt-profile" data-type="validation">Опишите свои впечатления об игре.</textarea>
                                                    <div style="float: right; margin: 5px -235px 0px 0px;" class="b-validation">
                                                        <div class="tooltip" id="description" style="margin-left: 28px;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <div style="float: right"><a href="javascript: void(0)" class="btn-login" id="send-completed-game">Продолжить</a>
                                            <a style="margin-left: 10px; background: #b4b4b4 !important;" href="javascript:closeModalAll()" class="btn-login">Отмена</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

