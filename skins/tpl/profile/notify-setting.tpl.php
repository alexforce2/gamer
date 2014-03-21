<h1>Notify</h1>
<?php $v=5?>
<h3>Отписаться от уведомлений</h3>

<form action="" method="POST">
    <table border="1">
        <tr>
            <td>Тип уведомления</td>
            <?=($_SESSION['user-data']['email']!=false)?'<td>email</td>':''?>
            <?=($_SESSION['user-data']['phone']!=false)?'<td>phone</td>':''?>
            <td>profile</td>
        </tr>
        <?php
        $i=0;
        foreach($data['available-setting'] as $availableSetting){
            $emailCheck = (isset($setting[$i]) && $setting[$i]->email!=false)?"checked":"";
            $phoneCheck = (isset($setting[$i]) && $setting[$i]->phone!=false)?"checked":"";
            $email = '<td><input type="checkbox" name="'.$availableSetting->name.'-email" value="1" '.$emailCheck.'/></td>';
            $phone = '<td><input type="checkbox" name="'.$availableSetting->name.'-phone" value="1" '.$phoneCheck.'/></td>';
            ?>
            <tr>
                <td><?=$availableSetting->title?></td>
                <?=($_SESSION['user-data']['email']!=false)? $email :''?>
                <?=($_SESSION['user-data']['phone']!=false)? $phone :''?>
                <td><input type="checkbox" name="<?=$availableSetting->name?>-profile" value="1" <?=(isset($setting[$i]) && $setting[$i]->profile!=false)?"checked":""?>/></td>
            </tr>
        <?php $i++; } ?>

    </table><br>
    <input type="submit" value="Сохранить"/>
</form>