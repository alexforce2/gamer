<?php $balance = (int)$_SESSION['user-data']['balance'];?>
<script type="text/javascript" >
    function actionChangeTariff()
    {
        $('#tariff').change(function() {
            var nameTarif = $("#tariff").val();
            switch(nameTarif)
            {
                case '1':
                    operationChangeTariff(400);
                break;
                case '2':
                    operationChangeTariff(500);
                break;
                case '3':
                    operationChangeTariff(700);
                break;
                case '4':
                    operationChangeTariff(1000);
                break;
                case '5':
                    operationChangeTariff(1500);
                break;
        }
        });
        $('#showform').fadeIn();
        $('body').append('<div id="fade"></div>');
        $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
        var popuptopmargin = ($('#showform').height() + 10) / 2;
        var popupleftmargin = ($('#showform').width() + 10) / 2;

        $('#showform').css({
            'margin-top' : -popuptopmargin,
            'margin-left' : -popupleftmargin
        });
    }
    function operationChangeTariff(sum)
    {
        $("#sum-pay").html(sum);
        var result = <?=$balance; ?> - sum;
        if(result < 0){
            $("#info-messge-modal").remove();
            $("#butt-action-modal").before('<div id="info-messge-modal"><img src="/skins/img/messgae-modal.png" /><div>Пожайлуста пополните<br />Ваш баланс</div></div>');
            $("#button-change-modal").html('<style>#showform{height: 250px}</style><a href="/profile/balance-up"><img src="/skins/img/butt-popolnenie-modal.png" /></a>');
        } else {
            $("#info-messge-modal").remove();
            $("#button-change-modal").html('<input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" />');
        }



        $("#balance-operation").html(result);
    }

    function closeModal(name){
        $('#fade , #'+name).fadeOut();
        return false;
    }
</script>

<div id="showform">
    <form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Окно сообщения</b>
        <img id="close-modal" onclick="closeModal('showform')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform">
        <b id="num-elements"></b><br />
        Текущий баланс - <b><?= $balance ?> руб.</b><br />
        Стоимость операции - <b id="sum-pay">400 руб.</b><br />
        Планируемый остаток <b style='color:red' id="balance-operation">
<?php
$resBalance = $balance - 400;
echo $resBalance;
?> руб.</b><br /><br />

<select name="tariff" id="tariff">
    <option value="1">До 10 объявлений</option>
    <option value="2">До 30 объявлений</option>
    <option value="3">До 50 объявлений</option>
    <option value="4">До 100 объявлений</option>
    <option value="5">До 200 объявлений</option>
</select>
    </div>
    <?php if ($resBalance < 0) { ?>
        <style>
            #showform{height: 250px;}
        </style>
        <div id="info-messge-modal">
            <img src="/skins/img/messgae-modal.png" />
            <div>Пожайлуста пополните<br />
                Ваш баланс</div>
        </div>
        <div id="butt-action-modal">
            <img onclick="closeModal('showform')" class="FL" src="/skins/img/butt-close-modal.png" />
            <div  id="button-change-modal"><a href="/profile/balance-up"><img src="/skins/img/butt-popolnenie-modal.png" /></a></div>
        </div>
    <? } else { ?>

        <div id="butt-action-modal" class="butt-action-modal">
            <img onclick="closeModal('showform')" class="FL" src="/skins/img/butt-close-modal.png" />
            <div id="button-change-modal"><input style="top: 10px; position: relative;" type="image" src="/skins/img/butt-oplata-modal.png" /></div>
        </div>
    <? } ?></form>
</div>
<div id="fade"></div>