<style>
    span .add{
        color:limegreen;
        cursor: pointer;
        font-size: large;
    }
    span .remove{
        color:red;
        cursor: pointer;
        font-size: large;
    }
    #added-users{
        border: 2px solid #34495E;
        width: 200px;
        padding: 5px;
        display: none;
    }

</style>
<script>
    $(document).ready(function(){
        $("#search-nick").keypress(function(e) {
            if(e.which == 13) {
                var search = $("#search-nick").val();
                if(search != '')
                {
                    $.ajax({
                        type: 'POST',
                        url: document.location.href,
                        dataType: 'html',
                        data: {'ajax-query': 'true', 'method': 'SearchUserAjax', 'type-class': 'model', 'search-nick': search},
                        beforeSend: function(){
                            $('#ajax-nick-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
                        },
                        success: function(data){
                            $("#ajax").remove();
                            var arr = $.parseJSON(data);
                            var res = '';
                            for(var k in arr){
                                res += "<div>" + arr[k].nick + " <span id='" + arr[k].nick + "/" + arr[k].id +"/"+ arr[k].email +"/"+ arr[k].phone + "' class='add'>+</span></div>";
                            }
                            $('#ajax-nick-result').append(res);
                        }
                    });
                }
            }
        });

        $("body").on("click", ".add", function(obj){
            var userId= obj.target.id.split("/");
            $(obj.target).parent().remove();
            if($("#added-users").children().length === 1)
                $("#added-users").css("display","block");
            var hidden="<input type='hidden' name='users[]' value='"+userId[0]+"/"+userId[1]+"/"+userId[2]+"/"+userId[3]+"'/>";
            var user="<div>"+userId[0]+"<span style='color: red; font-size: large; cursor: pointer;' class='remove'> - </span>"+hidden+"</div>";
            $("#added-users").append(user);

        });
        $("body").on("click", ".remove", function(obj){
            $(obj.target).parent().remove();
            if($("#added-users").children().length === 1)
                $("#added-users").css("display","none");
        });

        $("#send").on("click", function(){
            var data = $("#added-users").find("input[type=hidden]");
            var users = new Array();
            var notify = $.trim($("#notify-msg").val());
            if(notify === ""){
                alert("Заполните текстовое поле");
                return;
            }
            for(i in data){
                users[i]=data[i].value;
                if(parseInt(i)===(data.length-1))
                    break;
            }
            if( (Object.prototype.toString.call( users ) === '[object Array]') &&  users.length>0 )
            {
                $.ajax({
                    type: 'POST',
                    url: document.location.href,
                    dataType: 'html',
                    data: {'ajax-query': 'true', 'method': 'SendNotify', 'type-class': 'model', 'users': users, 'notify':notify},
                    beforeSend: function(){
                        $('#ajax-send-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
                    },
                    success: function(data){
                        $("#ajax").remove();
                    }
                });
            }
        });
    })

</script>
<h1>Уведомитель</h1>
<a href="notify/group">Массовые уведомления</a><br>
<a href="notify/history">Лог</a>
<div class="notify">
    <input type="text" id="search-nick" placeholder="Найти пользователя"/> <span id="ajax-nick-result"></span>
    <div id="added-users">
        <p>Список пользователей:</p>
    </div>
    <p>Текст уведомления:<br/>
    <textarea style="width: 400px;" id="notify-msg"></textarea>
    </p>
    <input type="button" value="Отправить" id="send"/><span id="ajax-send-result"></span>

</div>