<h1>Messages</h1>
<?php
foreach($data['notify'] as $notify){ ?>
    <p><i><b><a href="read-notify/<?=$notify->id?>"><?=$notify->msg?></a></b></i> Date:<?=date("d-m-Y G:i:s", $notify->date_send)?></p>
<?php } ?>