<h1>Лог уведомлений</h1>
<?php foreach($data as $notify){ ?>
    <p><b><?=$notify->nick?></b> Текст уведомления:<b><?=$notify->msg?></b> Дата:<b><?=date("d-m-Y G:i:s",$notify->date_send)?></b></p>
<?php } ?>