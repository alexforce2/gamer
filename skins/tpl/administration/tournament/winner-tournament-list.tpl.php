<?php
use classes\render;
use classes\url;
?>
<style>
.result{margin-top:20px;};

</style>
<script>
$(document).ready(function(){
    $('#select').on('change',function(){
        var id = $('#select').val();
        $('#list-winner,.result,.edit-button').html('').hide();
        $.ajax({
            type: 'POST',
            url: document.location.href,
            data: {'id':id},
            success: function(data){
                var arr = $.parseJSON(data);
                if($.isEmptyObject(arr)){
                    $('.result').html("<strong>Для данного турнира победители не определены</strong> | " +
                        "<a href='<?= Url::Action("winner-tournament-edit", "administration.tournament") ?>?id="+id+"'>Добавить</a>");
                }else{
                    $('.result').prepend("<strong>Выберите пользователя для редактирования</strong>");
                    for( var i in arr){
                        $('#list-winner').append("<p style='color:green;'><label style='cursor:pointer;'>" +
                            "<input name='winner' type='radio' value='"+arr[i].id_row+"'>" +
                            "<span>"+arr[i].place+" место</span> "+arr[i].nick+"</label></p>");
                    }
                }
                $('#list-winner,.result').show();
            }
        });
        $('#list-winner').on('change',function(){
            var idWinner = $(":radio:checked").val();
            $('.edit-button').html("<a class='edit' href='<?= Url::Action("winner-tournament-edit", "administration.tournament") ?>?id="+id+"&edit="+idWinner+"'>Редактировать</a> |" +
                "<a class='edit' href='<?= Url::Action("delete-winner", "administration.tournament") ?>?&edit="+idWinner+"'> Удалить</a> |" +
                "<a href='<?= Url::Action("winner-tournament-edit", "administration.tournament") ?>?id="+id+"'> Добавить</a>").show();
        })
    })
})
</script>
<div class="winner-list">
    <p>Выберите турнир</p>
    <select id="select" name="id_tournament">
        <? foreach($data['list-tournament'] as $res){
            echo "<option value='".$res->id."'>".$res->header."</option>";
        }?>
    </select>
    <div class="result"></div>
    <form id="list-winner"></form>
    <div class="edit-button" style="display:none;"></div>
</div>