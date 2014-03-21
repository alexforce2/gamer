<style>
    .link-green{color: #1abc9c;}
    .content-about {margin-left: 250px; position: relative;}
    .table-price-header{background-color: #002db2; height: 25px; color: white; font-size: 14px;}
    #table-price{width: 705px; text-align: center;}
    #table-price th{background-color: #002db2; height:25px; color: white; font-size: 14px}
    #table-price td{border: 1px solid #eeeeee; padding: 5px}
    .block-txt-price{padding: 10px;}
</style>
<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-about.block.tpl.php'; ?>
</div>
<div class="content-about">
    <table>
        <tr>

            <td class="cell-profile" >
                <table id="table-price"  cellpadding="0" cellspacing="0">
                    <tr id="table-price-header">
                        <th><span class="FL" style="margin-left:10px;">Описание</span></th>
                        <th style="width: 200px">Услуга</th>
                        <th style="width: 200px">Стоимость, руб</th>
                    </tr>
                    <tr class='notice-row-bg default-period' >
                        <td style="text-align: left;">Особенность текущего тарифа заключается в том, что при участии в платном on-line турнире
                            ваш тариф аннулируется. Тариф специально создан только для одной игры, в дальнейшем вы снова сможете его подключить.</td>
                        <td style="text-align: left;">Тариф «Группа С»</td>
                        <td>50 рублей</td>
                    </tr>
                    <tr class='notice-row-bg default-period'>
                        <td style="text-align: left;">Текущий тариф предоставляет доступ на месяц для участия в любых турнирах и конкурсах. По прошествию одного месяца тариф аннулируется.</td>
                        <td style="text-align: left;">Тариф «Группа X»</td>
                        <td>150 рублей</td>
                    </tr>

                </table>

                <div class="block-txt-price">
                    Способы пополнения счета личного кабинета.<br />
                    <b>Электронные платежи: терминалы, карты, электронные деньги:</b><br />
                    Через систему Robokassa<br />
                </div>

            </td>
        </tr>
    </table>
</div>