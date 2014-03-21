<?php
use classes\render;
use classes\url;
?>
<style>
    .header-games input{width: 945px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
    .input-link{width: 470px}
    .input-link input{width: 400px}
</style>

<h2><a style="text-decoration: none; color: #507fb6" href="<?= Url::Action("index", "administration.games") ?>"><?=$data['game']->name;?></a>
    -> <?=$data['game']->id > 0 ? "Редактирование главной страницы " : "Создание главной страницы" ?></h2>
<form action="<?= Url::Action("main-page", "administration.games") ?>" id="main-page-edit" method="POST">
    <?=Render::Hidden($data['game']->id, "id-game")?>
    <table>
        <tr>
            <td colspan="2">
                <div class="field fill header-games">
                    <?=Render::LabelEdit($data['main-page']->game_mode, "game_mode", "Режим игры", true)?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="field fill">
                    <?=Render::LabelEdit(date("d.m.Y", $data['main-page']->date_release_world), "date_release_world", "Дата выхода в мире", true)?>
                </div>
            </td>
            <td>
                <div class="field fill">
                    <?=Render::LabelEdit(date("d.m.Y", $data['main-page']->date_release_russia), "date_release_russia", "Дата выхода в россии", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher, "publisher", "Издатель", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_link, "publisher_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_russia, "publisher_russia", "Издатель в россии", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->publisher_russia_link, "publisher_russia_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->developer, "developer", "Разработчик", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->developer_link, "developer_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->official_site, "official_site", "Официальный либо другой сайт", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->official_site_link, "official_site_link", "Ссылка", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->game_engine, "game_engine", "Игровой движок", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->distribution, "distribution", "Распростронение", true)?>
                </div>
            </td>
        </tr>
    </table>
    <h4>Системные требования</h4>
    <table>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_os, "sr_os", "ОС", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_cpu, "sr_cpu", "CPU", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_ram, "sr_ram", "ОЗУ", true)?>
                </div>
            </td>
            <td class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_video, "sr_video", "Видеокарта", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="input-link">
                <div class="field fill">
                    <?=Render::LabelEdit($data['main-page']->sr_hdd, "sr_hdd", "HDD", true)?>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="field">
        <?=Render::LabelTextArea($data['main-page']->text, "text", "")?>
    </div>

    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['main-page']->title, "title", "Заголовок страницы")?>
    </div>
    <div class="field fill search-index" style="width: 100%; margin-right: 2%;">
        <?=Render::LabelEdit($data['main-page']->description, "description", "Описание страницы")?>
    </div>
    <div class="field fill search-index" style="width: 32%">
        <?=Render::LabelEdit($data['main-page']->keywords, "keywords", "Ключевые слова")?>
    </div>

    <div id="rubrics">


        <?foreach($data['rubrics'] as $r){ ?>

            <div class="rubric" style="margin-bottom: 10px;">
                <?php if($r['img']!=false){?>
                <div id="img-upload-btn-<?=$r['id']?>" class="container upload" style="display: none; margin-right: 80px;" size="original">
                    <span class="btn">Изображение</span>
                    <input id="img-files-<?=$r['id']?>" type="file" name="img-files[]"/>
                </div>
                <div class="edit-image" style="width: 200px;" name="show-btn">
                    <img width="150px" src="<?=$r['img']?>">
                    <input type="hidden" name="saved-img[]" value="<?=$r['id']."$".$r['img']?>$rubric">
                </div>
                <?php } ?>

                <?php if($r['img']==false){?>
                <div id="img-upload-btn-<?=$r['id']?>" class="container upload" style="display: inline-block; margin-right: 80px;" size="original">
                    <span class="btn">Изображение</span>
                    <input id="img-files-<?=$r['id']?>" type="file" name="img-files[]"/>
                </div>
                <?php } ?>

                <div class="field" style="display: inline-block;">
                    <?=Render::Hidden($r['id'], "id-rubrics[]")?>
                    <?=Render::LabelEdit($r['rubric'], "rubrics[]", "Рубрика игры", false); ?>
                </div>
                <div class="field" style="display: inline-block;">
                    <a href="javascript:void(0)" class="remove-rubric" id="<?=$r['id']?>">Удалить</a>
                </div>
            </div><div class="clear"></div>

            <? } ?>

    </div>
    <div class="field" style="padding: 10px 10px 10px 0px; display: block;">
        Новых рубрик: <input type="text" id="rubrics-count" style="width: 30px;">
        <input type="button" id="add-rubric" value="создать">
        <p style="display: none; color: red; margin-left: 70px;" id="warning">Заполните поле!</p>
    </div>

    <div style="border: solid 1px #34495E;">
        <h2>Screeshots</h2>
        <?php if($data['screenshot']!=false){
                $screenCount = count($data['screenshot']);
                $newScreenCount = (int)$data['screenshot-count']-$screenCount;
                $i=0;
                foreach($data['screenshot'] as $screenshot){?>
                <div style="width: 180px; height: 180px; display: inline-block;">
                    <div id="screen-upload-btn-<?=$i?>" class="container upload" style="display: none;">
                        <span class="btn">Скриншот</span>
                        <input id="screen-file-<?=$i?>" type="file" name="screen-file[]"/>
                    </div>
                    <div class="edit-image" style="width: 200px;" name="show-btn">
                        <img src="<?=$screenshot['screenshot_s']?>">
                        <input type="hidden" name="saved-screen[]" value="<?=$screenshot['id']."$".$screenshot['screenshot_s']?>$screen">
                    </div>
                </div>
                <?php
                    $i++;
                }

                if($newScreenCount!==0){
                    while($newScreenCount > 0){?>

                        <div style="width: 180px; height: 180px; display: inline-block;">
                            <div id="screen-upload-btn-<?=$i?>" class="container upload">
                                <span class="btn">Скриншот</span>
                                <input id="screen-file-<?=$i?>" type="file" name="screen-file[]"/>
                            </div>
                        </div>

                        <?php $newScreenCount--;
                        $i++;
                    }
                }
            }else{
                    for($newScreen = (int)$data['screenshot-count'], $i = 0; $newScreen > 0; $newScreen--, $i++){?>
                        <div style="width: 180px; height: 180px; display: inline-block;">
                        <div id="screen-upload-btn-<?=$i?>" class="container upload" >
                            <span class="btn">Скриншот</span>
                            <input id="screen-file-<?=$i?>" type="file" name="screen-file[]"/>
                        </div>
                        </div>

              <?php }
            } ?>
    </div>


    <div id="img-poster-btn" class="container upload" style="display: <?=($data['main-page']->video_img==false)?'block':'none'?>" size="original">
        <span class="btn">Постер видео</span>
        <input id="img-poster" type="file" name="img-poster"/>
    </div>
    <?php if( $data['main-page']->video_link!=false) { ?>
        <div id="video-upload-btn" class="container upload" style = "display: none;">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>

        <div class="span8 demo-video" style="position: relative; top: 22px;">
            <div class="" style="position: relative; bottom: 2px;">
                <video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster="<?=($data['main-page']->video_img != false)?$data['main-page']->video_img:''?>" data-setup="{}">
                    <source src="<?=$data['main-page']->video_link?>" type='video/mp4' />
                </video>
            </div>
            <input type="hidden" name="video-link" value="<?=$data['main-page']->video_link?>">
            <input type="hidden" name="video-img" value="<?=($data['main-page']->video_img != false) ? $data['main-page']->video_img : '' ?>">
            <div style="height: 50px; width: 100%">
                <input type="button" value="Удалить видео" id="delete-video">
            </div>
        </div><div style="clear: both;"></div><br>
    <?php } else{ ?>
        <div id="video-upload-btn" class="container upload" style = "">
            <span class="btn">Видеофайл</span>
            <input id="video-file" type="file" name="video-file"/>
        </div>
    <?php } ?>

    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>


</form>
<? include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/main-modal.block.tpl.php"; ?>
<style>
    .edit-image{float: left; margin: 10px;}
</style>

<script type="text/javascript">
    $(document).ready(function(){
        initMultiUploader(config);

        $('#delete-video').click(function(){
            $("#img-poster-btn").show();
            var removeVideoLink = $('input[name=video-link]').val();
            var removeVideoImg = $('input[name=video-img]').val();
            if(removeVideoLink !== undefined && removeVideoImg !== undefined){
                $('form').append("<input type='hidden' name='deleted-video-link' value='"+removeVideoLink+"'>");
                $('form').append("<input type='hidden' name='deleted-video-img' value='"+removeVideoImg+"'>");
            }
            $(this).closest(".demo-video")[0].remove();
            $("#video-file").parent().show();
        });

        $("body").on("click", ".remove-rubric", function(){
            var id = $(this).attr('id');
            if(id!== undefined)
                $('form').append("<input type='hidden' name='deleted-rubrics[]' value='"+id+"'>");
            $(this).closest("div.rubric").remove();
        });

        $("#add-rubric").click(function(){
            var imgRubricCount = parseInt($("#rubrics-count").val());
            var i=0;
            if(isNaN(imgRubricCount)){
                $("#warning").show();
                $("#rubrics-count").focus();
                $("#rubrics-count").css("border", "solid red 2px");
                return false;
            }
            while(i < imgRubricCount){
                debugger;
                var html = '<div class="rubric">' +
                                '<div id="add-img-upload-btn-'+ i +'" class="container upload" style="display: inline-block; margin-right: 80px;" size="original">' +
                                    '<span class="btn">Изображение</span>' +
                                    '<input id="add-img-files-'+ i +'" type="file" name="new-img-files[]"/>' +
                                '</div>' +
                                '<div class="field" style="display: inline-block;">' +
                                    '<label>Рубрика игры</label><br>' +
                                    '<input type="text" value="" name="new-rubrics[]">' +
                                '</div>' +
                                '<div class="field" style="display: inline-block;"><a href="javascript:void(0)" class="remove-rubric">Удалить</a></div>' +
                            '</div>' +
                            '<div style="clear: both;"></div>';
                $("#rubrics").append(html);
                i++;
            }
            initMultiUploader(config);
            $("#add-rubric").parent().hide();
        });
    })
    // todo: сделать порог входящих файлов
    // todo: сделать мультизагрузку файлов
    var config = {
        form: "main-page-edit",
        visualProgress: "modal",
        img: true,
        method: "MainPageGame",
        multi: false,
        limit: 1,
        uploadUrl: document.location.href
    };
</script>