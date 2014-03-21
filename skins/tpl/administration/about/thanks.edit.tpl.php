<?php
use classes\render;
use classes\url;
?>
<script>

    $(document).ready(function(){
        $('#submit').on('click',function(e){
            var data = $('#myFormThanks').serializeArray();
            $("#myFormThanks").find("input,textarea").val('');
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {'data':data},
                success: function(){
                    window.location.replace("/administration/about/thanks");
                }

            });
            e.preventDefault();
        });
        initMultiUploader(config);
    });
    var config = {
        form: "myFormThanks",
        dragArea: "dragAndDropFiles",
        visualProgress: "modal",
        img: true,
        method: "UploadImg",
        uploadUrl: document.location.href
    }

</script>
<style>
    .style-input input{width:300px;}
    .text textarea{width:500px;height:100px;}
</style>


<h1><a href="<?= Url::Action("thanks", "administration.about") ?>">Партнёры</a> -> <?=$data->id > 0 ? "Редактирование" : "Добавление" ?></h1>
<?=Render::Hidden($data->id, "id")?>


<form action="" id="myFormThanks" method="POST" enctype="multipart/form-data">
    <?=Render::Hidden($data->id, "id")?>
    <div class="field" style="margin: 30px 0 30px;">
        <?if(empty($data->source_img)){?>
            <div id="img-upload-btn" class="container upload">
                <span class="btn">Изображение</span>
                <input id="img" type="file" name="img" />
            </div>
        <?}else{?>
            <div style="padding: 10px">
                <div class="edit-image ">
                    <img src="/storage/thanks/<?=$data->source_img;?>">
                    <input type="hidden" name="img" value="<?=$data->source_img;?>">
                </div>
            </div>
        <?}?>
    </div>
        <div class="style-input">
            <?=Render::LabelEdit($data->name_partner, "name_partner", "Имя", true)?>
        </div>
        <br>
    <div class="style-input">
        <?=Render::LabelEdit($data->link_anchor, "link_anchor", "Текст ссылки", true)?>
    </div>
    <div class="style-input">
        <?=Render::LabelEdit($data->link,  "link", "Ссылка", true)?>
    </div>
    <div class="text">
        <?=Render::LabelTextArea($data->text, "description", "Описание")?>
    </div>
    <div style="height: 50px; width: 100%">
        <button id="submit" class="right">Отправить</button>
    </div>

</form>
<div class="result"></div>
