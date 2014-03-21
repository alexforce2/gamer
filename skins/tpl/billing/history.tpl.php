<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-billing.block.tpl.php'; ?>
</div>
<?php
if (empty($data)) {
    echo <<<HTML
    <div>
        <span style="position: relative; left: 30px; font-size: 24px;">История пуста</span>
    </div>
    <span style="position: relative; left: 30px; font-size: 13px;">
    Вы еще не производили платежей, попробуйте <a href="/billing">пополнить счет</a>.
    </span>
HTML;

} else {

    $transactionHtml = '';
    foreach ($data as $transaction) {
        $data = new DateTime($transaction->data);
        $data = $data->format("d F Y");

        $transactionHtml .= <<<HTML
        <tr>
            <td>{$transaction->id}</td>
            <td>{$data}</td>
            <td>{$transaction->description}</td>
            <td>{$transaction->sum}</td>
        </tr>
HTML;
    }

    echo <<<HTML
    <div>
        <span style="position: relative; left: 30px; font-size: 24px;">История операций</span>
    </div>
    <table style="position: relative; left: 30px; text-align: center; width: 650px">
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Описание</th>
            <th>Сумма</th>
        </tr>
        {$transactionHtml}
    </table>
HTML;

}