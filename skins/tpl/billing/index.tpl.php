<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-billing.block.tpl.php';
    $nameProfile = $this->user['last_name'] . " " . $this->user['first_name'] . " " . $this->user['patronymic'];
    ?>
</div>
<?php
if(isset($_GET['static'])){
?>
<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
 <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="10.00">
 <input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="0YLQtdGB0YLQvtCy0YvQuSDRgtC+0LLQsNGA">
 <input type="hidden" name="LMI_PAYEE_PURSE" value="R658216622390">
 
 <input type="hidden" name="LMI_PAYMENT_NO" value="234">
<input type="submit" class="wmbtn" style="font-famaly:Verdana, Helvetica, sans-serif!important;padding:0 10px;height:30px;font-size:12px!important;border:1px solid #538ec1!important;background:#a4cef4!important;color:#fff!important;" value=" &#1086;&#1087;&#1083;&#1072;&#1090;&#1080;&#1090;&#1100; 10.00 WMR ">
</form>
<?}?>
<div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Выберите способ зачисления средств на счёт
    </span>
</div>
<span style="position: relative; left: 30px; font-size: 13px;">
    Зачисление происходить с помощью электронной системы платежей ROBOKASSA
</span>
<div style="width: 400px; padding-top: 14px; padding-left: 20px;" class="left">

    <div class="left" style="width: 600px">
            <? if(isset($_GET['pay'])){ ?>
            <table>
                <tr>
                    <td>Пользователь:</td>
                    <td><?=$nameProfile;?></td>
                </tr>
                <tr>
                    <td>Описание:</td>
                    <td><?=$data['desc'];?></td>
                </tr>
                <tr>
                    <td>Сумма:</td>
                    <td><?=$data['sum'];?> рублей</td>
                </tr>
                <tr>
                    <td>Номер счёта:</td>
                    <td><?=$data['pay-id'];?></td>
                </tr>
                </table>
                    <form action="https://auth.robokassa.ru/Merchant/Index.aspx" method="post">
                        <input type="hidden" name="MerchantLogin" value="<?= $data['login']; ?>">
                        <input type="hidden" name="OutSum" value="<?= $data['sum']; ?>">
                        <input type="hidden" name="InvId" value="<?= $data['pay-id']; ?>">
                        <input type="hidden" name="Desc" value="<?= $data['desc']; ?>">
                        <input type="hidden" name="SignatureValue" value="<?= $data['crc']; ?>">
                        <input type="submit" class="btn-login left" value="Пополнить" style="border: 0">
                    </form>

        <? }else { ?>
            <img src="/skins/img/robokassa.jpg" style="width: 450px;"/><br><br>
            <a href="/billing/?pay" class="btn-login left">Продолжить</a>
            <div style="color: #828b8c; font-size: 10px; margin-left: 140px">
                Предупреждаем, что при зачислении некоторых платёжных систем может
                присутствовать коммисия, эта коммисия внутри самих провайдеров
            </div>
        <? } ?>

    </div>
</div>

<div style="width: 250px;" class="right">
    <h3>Особенности зачисления:</h3>
    <img class="left" src="/skins/img/interface/closed-marker.png">

    <div style="width: 98%; position: relative; bottom: 5px; left: 10px;">Можно оплачивать: Яндекс.Деньги, Visa и др.;
    </div>
    <img class="left" src="/skins/img/interface/closed-marker.png">

    <div style="width: 98%; position: relative; bottom: 5px; left: 10px;">Оплата зачисляется в режиме online;</div>
<!--    <img class="left" src="/skins/img/interface/closed-marker.png">-->

<!--    <div style="width: 98%; position: relative; bottom: 5px; left: 10px;">Комиссия отсутствует;</div>-->
</div>