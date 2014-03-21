<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-billing.block.tpl.php';
    $nameProfile = $this->user['last_name'] . " " . $this->user['first_name'] . " " . $this->user['patronymic'];
    ?>
</div>
<? if (!empty($data['pay'])) { ?>
    <div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Успешное зачисление средств
    </span>
    </div>
	<span style="position: relative; left: 30px; font-size: 13px;">
		Номер вашего платежа <b><?=$data['pay'];?>.</b>
	</span><br>
    <span style="position: relative; left: 30px; font-size: 13px;">
		На ваш счёт была зачисленна сумма в размере 50 рублей.
	</span>
<? } else { ?>
    <div>
        <span style="position: relative; left: 30px; font-size: 24px;">
            Внутренняя ошибка реквизитов
        </span>
    </div>
<? } ?>