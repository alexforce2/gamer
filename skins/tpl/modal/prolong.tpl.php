<?php $balance = (int) $_SESSION['user-data']['balance'];?>
<script type="text/javascript" >        
    function prolongAdvert()
    {
        debugger;
        var arrCheck = $('.advert-check:checked');
        var arrCheckParam = $('.check-public:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-prolong').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform-up').height() + 10) / 2;
            var popupleftmargin = ($('#showform-up').width() + 10) / 2;

            $('#showform-prolong').css({
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
            operationChangeProlong(sum);
            $("#prolong-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#prolong-hidden-value").append('<input type="hidden" name="param-chek" value="' + myCheckParam + '">');

            $("#num-elements-prolong").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
            alert('Выберите хотя бы один элемент, если параметры не будут заданы, то объявление автоматически будет размещенно на всех ресурсах.');
        
    }
    
    function operationChangeProlong(sum)
    {
        debugger;
        $("#sum-pay-prolong").html(sum);
        var result = <?=$balance; ?> - sum;
        if(result < 0){
            $("#info-messge-modal-prolong").remove();
            $("#butt-action-modal-prolong").before('<div id="info-messge-modal-up" class="info-messge-modal"><img src="/skins/img/messgae-modal.png" /><div>Пожайлуста пополните<br />Ваш баланс</div></div>');
            $("#button-change-modal-prolong").html('<style>#showform-prolong{height: 260px}</style><a href="/profile/balance-up"><img src="/skins/img/butt-popolnenie-modal.png" /></a>');
        } else {
            $("#info-messge-modal-prolong").remove();
            $("#button-change-modal-prolong").html('<style>#showform-prolong{height: 210px}</style><input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />');
        }
            
        
        
        $("#balance-operation-prolong").html(result);
    }
    function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }
    
</script>

<div id="showform-prolong">
    <form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Окно сообщения</b>
        <img id="close-modal" onclick="closeModal('showform-prolong')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform">
        Вы действительно хотите продлить выбранные объявления?<br /><br />
        <b id="num-elements-prolong"></b><br />
        Текущий баланс - <b><?= $_SESSION['user-data']['balance']; ?> руб.</b><br />
        Стоимость операции - <b id="sum-pay-prolong">100 руб.</b><br />
        Планируемый остаток <b style='color:red' id="balance-operation-prolong"></b><br />
    </div>    
        <div id="butt-action-modal-prolong" class="butt-action-modal">
            <img onclick="closeModal('showform-up')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="prolong-hidden-value"></div>
            <input type="hidden" name="method" value="ProlongAdvert" />
            <div id="button-change-modal-prolong">
                <input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />
            </div>
        </div>
        
    </form>
</div>
<div id="fade"></div>