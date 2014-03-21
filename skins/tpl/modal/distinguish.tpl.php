<?php $balance = (int) $_SESSION['user-data']['balance'];?>
<script type="text/javascript" >        
    function distinguishAdvert()
    {
        debugger;
        var arrCheck = $('.advert-check:checked');
        var arrCheckParam = $('.check-public:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-distinguish').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform-up').height() + 10) / 2;
            var popupleftmargin = ($('#showform-up').width() + 10) / 2;

            $('#showform-distinguish').css({
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
            operationChangeDistinguish(sum);
            $("#distinguish-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#distinguish-hidden-value").append('<input type="hidden" name="param-chek" value="' + myCheckParam + '">');

            $("#num-elements-distinguish").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
            alert('Выберите хотя бы один элемент, если параметры не будут заданы, то объявление автоматически будет размещенно на всех ресурсах.');
        
    }
    
    function operationChangeDistinguish(sum)
    {
        debugger;
        $("#sum-pay-distinguish").html(sum);
        var result = <?=$balance; ?> - sum;
        if(result < 0){
            $("#info-messge-modal-distinguish").remove();
            $("#butt-action-modal-distinguish").before('<div id="info-messge-modal-up" class="info-messge-modal"><img src="/skins/img/messgae-modal.png" /><div>Пожайлуста пополните<br />Ваш баланс</div></div>');
            $("#button-change-modal-distinguish").html('<style>#showform-distinguish{height: 260px}</style><a href="/profile/balance-up"><img src="/skins/img/butt-popolnenie-modal.png" /></a>');
        } else {
            $("#info-messge-modal-distinguish").remove();
            $("#button-change-modal-distinguish").html('<style>#showform-distinguish{height: 210px}</style><input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />');
        }
            
        
        
        $("#balance-operation-distinguish").html(result);
    }
    function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }
    
</script>

<div id="showform-distinguish">
    <form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Окно сообщения</b>
        <img id="close-modal" onclick="closeModal('showform-distinguish')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform">
        Вы действительно хотите выделить выбранные объявления?<br /><br />
        <b id="num-elements-distinguish"></b><br />
        Текущий баланс - <b><?= $_SESSION['user-data']['balance']; ?> руб.</b><br />
        Стоимость операции - <b id="sum-pay-distinguish">100 руб.</b><br />
        Планируемый остаток <b style='color:red' id="balance-operation-distinguish"></b><br />
    </div>    
        <div id="butt-action-modal-distinguish" class="butt-action-modal">
            <img onclick="closeModal('showform-up')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="distinguish-hidden-value"></div>
            <input type="hidden" name="method" value="DistinguishAdvert" />
            <div id="button-change-modal-distinguish">
                <input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />
            </div>
        </div>
        
    </form>
</div>
<div id="fade"></div>