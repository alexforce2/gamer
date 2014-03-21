<script>

    $(document).ready(function(){
        var usersCount = $("#users-count").val();
        $("#create-groups").on("click", function(obj){
            //alert(usersCount);
            var count = parseInt($("#count").val());
            var parts = Math.ceil(usersCount/count);
            var partEnd = parts;
            //alert(parts);
            var i=0;
            var part=1;
            while(i<parts){
                var limitStart = count*i;
                var limitEnd = (i === partEnd-1) ? usersCount-limitStart : count;
                var button = "<input type='button' id='" + limitStart + "-" + limitEnd + "' class='send-part' value='"+part+"-ая часть'>";
                $("#parts").append(button);
                i++;
                part++;
            }
            $("#input-field").hide();
        });

        $("body").on("click", ".send-part", function(obj){
            var limit = $(obj.currentTarget).attr("id").split("-");
            var notify = $.trim($("#notify-msg").val());
            if(notify === ""){
                alert("Заполните текстовое поле");
                return;
            }
            //alert(limit[0]+limit[1]);
            $.ajax({
                type: 'POST',
                url: document.location.href,
                dataType: 'html',
                data: {'ajax-query': 'true', 'method': 'SendNotifyGroup', 'type-class': 'model', 'limit-start': limit[0], 'limit-end':limit[1], 'notify':notify},
                success: function(data){
                    var arr = $.parseJSON(data);
                    $(obj.currentTarget).hide();
                }
            });
        });
    });
</script>
<h1>Массовые уведомления</h1>
<p id="parts">Количество пользователей <?=$data['users_count']?><input type="hidden" value="<?=$data['users_count']?>" id="users-count"/><br/>
    <p id="input-field">Пользователей в группе<input type="text" id="count"/><input type="button" value="создать" id="create-groups"/></p>
</p>
<p>Текст уведомления:<br/>
    <textarea style="width: 400px;" id="notify-msg"></textarea>
</p>
