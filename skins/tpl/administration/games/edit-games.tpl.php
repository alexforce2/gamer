<?php
use classes\render;
use classes\url;
?>
<style>
    #header-games input{width: 774px;}
    #announce-games textarea{width: 774px;}
    .search-index input {width: 948px;}
</style>

<h1><a href="<?= Url::Action("index", "administration.games") ?>"><?=$data['game']->name; ?></a>
    <?=$data['game']->id > 0 ? " -> Редактирование" : "Создание" ?></h1>
<form action="<?= Url::Action("edit-main-game", "administration.games") ?>" method="POST" enctype="multipart/form-data" id="edit-main-game">
    <?=Render::Hidden($data['game']->id, "id")?>
    <?=Render::Hidden((!empty($_GET['id']) ? $_GET['id'] : 0), "game_id")?>

    <table class="game-manage">
        <tr>

            <td>
                <div class="field fill" id="header-games">
                    <?=Render::LabelEdit($data['game']->name, "name", "Название игры", true)?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" id="genre">
                    <select name="genre_id">
                        <?
                        foreach($genre as $r)
                        {
                            $selected = ($data['game']->genre_id == $r->id) ? "selected" : "";
                            echo "<option value='".$r->id."' ".$selected.">".$r->name."</option>";
                        }
                        ?>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field" style="float: left; padding: 10px">
                    <?
                    if(empty($data['game']->source_img_s)){
                        ?>
                        <div id="icon-upload-btn" class="container upload">
                            <span class="btn">Иконка</span>
                            <input id="source_img_s" type="file" name="source_img_s" />

                        </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image">
                                <img src="/storage<?=$data['game']->source_img_s;?>">
                                <input type="hidden" name="source_img_s" value="<?=$data['game']->source_img_s;?>">
                            </div>
                        </div>
                    <?}?>
                    <?
                    if(empty($data['game']->source_img_b)){
                        ?>
                        <br><br>
                        <div id="img-upload-btn" class="container upload">
                            <span class="btn">Изображение</span>
                            <input id="source_img_b" type="file" name="source_img_b" />
                        </div>
                    <?}else{?>
                        <div style="padding: 10px">
                            <div class="edit-image">
                                <img src="/storage<?=$data['game']->source_img_b;?>">
                                <input type="hidden" name="source_img_b" value="<?=$data['game']->source_img_b;?>">
                            </div></div>

                    <?}?>
                    <div id="info" style="padding: 10px;"></div>
                </div>
            </td>
        </tr>
        <? if(empty($data['difficulty'])){?>
            <tr class="add-difficulty">
                <td>
                    <div class="field" style="float: left; padding: 10px">
                        <?=Render::LabelEdit("Отсутствует", "name-difficulty[]", "Уровень сложности", false); ?>
                    </div>
                    <div class="field" style="float: left; padding: 10px">
                        <?=Render::LabelEdit("Отсутствует", "description-difficulty[]", "Описание уровня сложности", false, array("style"=>"width:600px;")); ?>
                    </div>
                </td>
            </tr>
        <? } if(!empty($data['difficulty'])){ ?>
        <? $i=0; foreach($data['difficulty'] as $r){ ?>
            <tr class="add-difficulty">
                <td>
                    <div class="field" style="float: left; padding: 10px">
                        <?=Render::Hidden($r->id, "id-delete")?>
                        <?=Render::LabelEdit($r->name, "name-difficulty[]", "Уровень сложности", false); ?>
                    </div>
                    <div class="field" style="float: left; padding: 10px">
                        <?=Render::LabelEdit($r->description, "description-difficulty[]", "Описание уровня сложности", false, array("style"=>"width:600px;")); ?>
                    </div>
                    <? if($i > 0){ ?>
                    <div class="field" style="float: left; position: relative; top: 40px;">
                        <a href="javascript:void(0)" class="remove-difficulty">Удалить</a>
                    </div>
                    <? } ?>
                </td>
            </tr>
        <? ++$i; } }?>

    </table>
    <div class="field" style="padding: 10px">
        <input type="button" id="add-difficulty" value="Добавить ещё уровень сложности">
    </div>
    <div style="height: 50px; width: 100%">
        <input type="submit" value="Сохранить" class="right">
    </div>
</form>
<? include $_SERVER["DOCUMENT_ROOT"]. "/skins/tpl/block/main-modal.block.tpl.php"; ?>
<script type="text/javascript">
    var config = {
        form: "edit-main-game",
        dragArea: "dragAndDropFiles",
        visualProgress: "modal",
        img: true,
        method: "UploadImg",
        uploadUrl: document.location.href
    }

    $(document).ready(function(){
        $("body").on("click", ".remove-difficulty", function(){
            //debugger;
            var id = $(this).closest("tr").find("input[type=hidden]").val();
            $('form').append("<input type='hidden' name='delete-field[]' value='"+id+"'>");
            $(this).closest("tr").remove();
        });
        $("#add-difficulty").click(function(){
            var element = $(".add-difficulty")[1];
            var html = '<tr class="add-difficulty"><td><div class="field" style="float: left; padding: 10px"><label>Уровень сложности</label><br><input type="text" value="" name="name-difficulty[]"></div><div class="field" style="float: left; padding: 10px"><label>Описание уровеня сложности</label><br><input style="width:600px;" type="text" value="" name="description-difficulty[]"></div><div class="field" style="float: left; position: relative; top: 40px;"><a href="javascript:void(0)" class="remove-difficulty">Удалить</a></div></td></tr>';
            $(".game-manage tbody").append(html);
            //$(cloneEl).find("input[type=text]").val("");
        });
    })

    $(document).ready(function(){
        initMultiUploader(config);
    });
</script>

