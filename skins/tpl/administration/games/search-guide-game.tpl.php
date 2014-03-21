<?php
use classes\render;
use classes\url;
?>
<script>
    $(document).ready(function(){
        $("#search-game").keypress(function(e) {
            if(e.which == 13) {
                var search = $("#search-game").val();
                if(search != '')
                {
                    $.ajax({
                        type: 'POST',
                        url: document.location.href,
                        dataType: 'html',
                        data: {'ajax-query': 'true', 'method': 'SearchGameAjax', 'type-class': 'model', 'search-game': search},
                        beforeSend: function(){
                            $('#ajax-game-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
                        },
                        success: function(data){
                            debugger;
                            $("#ajax").remove();
                            var arr = $.parseJSON(data);
                            var res = '';
                            for(var k in arr){
                                res += "<div><a href='/administration/games/guide-game/"+arr[k].id+"'>"+arr[k].name+"<div>";
                            }
                            $('#ajax-game-result').append(res);
                        }
                    });
                }
            }
        });
    })

</script>
<h1>Список игровых обзоров</h1>
<div id="list">
    <input type="text" id="search-game" placeholder="Найти игру"/> <span id="ajax-game-result"></span>
</div>


