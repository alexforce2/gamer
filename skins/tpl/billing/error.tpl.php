<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-billing.block.tpl.php'; ?>
</div>
<div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Внутренняя ошибка платежа
    </span>
</div>
<span style="position: relative; left: 30px; font-size: 13px;">
    Если вам не удалось пополнить счёт, но вы уверены что сделали всё правильно, пожалуйста напишите нам <a href="mailto:support@gs11.ru" style="color: #507fb6; text-decoration: none">письмо</a>
    Указав ваш номер платежа <b><?=$data['pay']; ?></b>
</span>