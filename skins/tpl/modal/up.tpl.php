<?php $balance = (int) $_SESSION['user-data']['balance'];?>
<script type="text/javascript" >        
    function actionUpAdvert()
    {
        debugger;
        var arrCheck = $('.advert-check:checked');
        var arrCheckParam = $('.check-public:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-up').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform-up').height() + 10) / 2;
            var popupleftmargin = ($('#showform-up').width() + 10) / 2;

            $('#showform-up').css({
                'margin-top' : -popuptopmargin,
                'margin-left' : -popupleftmargin
            });
            var myCheck = new Array();
            arrCheck.each( function() {                
                myCheck += this.value + ',';                
                });
            var myCheckParam = new Array();
            arrCheckParam.each( function() {                
                myCheckParam += this.value + ',';                
                });
                var sum = arrCheck.length * 100;
            operationChangeUp(sum);
            $("#up-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#up-hidden-value").append('<input type="hidden" name="param-chek" value="' + myCheckParam + '">');

            $("#num-elements-up").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
            alert('Выберите хотя бы один элемент, если параметры не будут заданы, то объявление автоматически будет размещенно на всех ресурсах.');
        
    }
    
    function operationChangeUp(sum)
    {
        debugger;
        $("#sum-pay-up").html(sum);
        var result = <?=$balance; ?> - sum;
        if(result < 0){
            $("#info-messge-modal-up").remove();
            $("#butt-action-modal-up").before('<div id="info-messge-modal-up" class="info-messge-modal"><img src="/skins/img/messgae-modal.png" /><div>Пожайлуста пополните<br />Ваш баланс</div></div>');
            $("#button-change-modal-up").html('<style>#showform-up{height: 260px}</style><a href="/profile/balance-up"><img src="/skins/img/butt-popolnenie-modal.png" /></a>');
        } else {
            $("#info-messge-modal-up").remove();
            $("#button-change-modal-up").html('<style>#showform-up{height: 210px}</style><input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />');
        }
            
        
        
        $("#balance-operation-up").html(result);
    }
    function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }
    
</script>

<div id="showform-up">
    <form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Окно сообщения</b>
        <img id="close-modal" onclick="closeModal('showform-up')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform">
        Вы действительно хотите поднять выбранные объявления?<br /><br />
        <b id="num-elements-up"></b><br />
        Текущий баланс - <b><?= $_SESSION['user-data']['balance']; ?> руб.</b><br />
        Стоимость операции - <b id="sum-pay-up">100 руб.</b><br />
        Планируемый остаток <b style='color:red' id="balance-operation-up"></b><br />
    </div>    
        <div id="butt-action-modal-up" class="butt-action-modal">
            <img onclick="closeModal('showform-up')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="up-hidden-value"></div>
            <input type="hidden" name="method" value="UpAdvert" />
            <div id="button-change-modal-up">
                <input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />
            </div>
        </div>
        
    </form>
</div>
<div id="fade"></div>
