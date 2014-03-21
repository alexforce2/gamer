<script>
    $(document).ready(function(){
        $('.close-message').on('click',function(e){
            var id = $(this).attr('id');
            $(this).parent().slideUp(300);

            $.ajax({
                type: 'POST',
                data: {'id':id},
                url: document.location.href
            })
        })

    });
    function show(){
        $('.message-block').toggle();
    }
</script>



<style>
    .message-block{display:none;}
    .mess{border:1px solid black;padding:5px;margin:2px;width:300px;}
    .result{padding:5px;border:1px solid black;margin:5px;position:relative;}
    .message{position:absolute;left:330px;top:20px;}
    .close-message{position:absolute;right:5px;top:1px;cursor:pointer}

</style>
<h3 onclick="show()">Непрочитанные сообщения <span style="color:red;">(<?echo $data['count_message'][0][0]?>)</span></h3>
<div class="message-block">
        <?foreach($data['message_contact'] as $key => $value){?>
            <div class="result">
                <div class="mess"><b>Рубрика:</b> <?echo $value['id_rubric']?><?echo $value['name_rubric']?></div>
                <div class="mess"><b>User:</b> <?echo $value['nick']?></div>
                <div class="mess"><b>Имя:</b> <?echo $value['name_user']?></div>
                <div class="mess"><b>E-mail:</b> <?echo $value['email']?></div>
                <div class="message"><?echo $value['text']?></div>
                <span class="close-message" id="<?echo $value['id']?>"> Закрыть</span>
            </div>
        <?}?>
</div>


















