<div class="left">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-billing.block.tpl.php'; ?>
</div>
<?php if($this->user['group'] == 1){ ?>
    <div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Ваш текущий тариф <b>Группа С</b>:
    </span>
    </div>
    <span style="position: relative; left: 30px; font-size: 13px;">
    Для того что бы сменить тариф необходимо, сначала воспользоваться текущим тарифом.
</span>
<?php } else if($this->user['group'] == 2){ ?>
    <div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Ваш текущий тариф <b>Группа X</b>:
    </span>
    </div>
    <span style="position: relative; left: 30px; font-size: 13px;">
    Для того что бы сменить тариф необходимо, сначала воспользоваться текущим тарифом.
</span>
<?php } else { ?>

    <script type="text/javascript">
        function setTariff(){
            var tariff = $("input[name=tariff]:checked").val();
            $.ajax({
                type: 'POST',
                url: document.location.href,
                dataType: 'html',
                data: {'ajax-query': 'true', 'method': 'SetTariff', 'type-class': 'model', 'tariff': tariff},
                beforeSend: function(){
                    $('#ajax-login-result').html('<img id="ajax" src="/skins/img/ajax.gif">');
                },
                success: function(data){
                    $("#ajax").remove();
                    if(data != ""){
                        $('#ajax-result').html(data);
                    }else{
                        location.reload();
                    }
                }
            });
        }
    </script>
    <div>
    <span style="position: relative; left: 30px; font-size: 24px;">
        Выберите тариф для подключения:
    </span>
    </div>
    <span style="position: relative; left: 30px; font-size: 13px;">
    Пожалуйста ознакомтесь с каждым тарифом, для избежания недопониманий.
</span>
    <div style="padding-top: 14px; padding-left: 20px;" class="left">
        <div style="margin-left: 25px">
            <label class="radio">
                <input type="radio" name="tariff" value="1">Тариф <b>Группа С</b> <i style="color: gray; font-size: 12px;">с вашего счёта спишется 50 рублей</i>
            </label>
        </div>
        <div style="margin-top: 10px;">
            <label class="radio" style="margin-left:25px">
                <input type="radio" name="tariff" value="2">Тариф <b>Группа X</b>  <i style="color: gray; font-size: 12px;">с вашего счёта спишется 150 рублей</i>
            </label>
        </div><br>
        <a href="javascript:setTariff()" class="btn-login left">Продолжить</a>
        <div style="color: #828b8c; font-size: 10px; margin-left: 140px">
            Любая оперция внутри системы GS11 использует внутреннюю валюту, и не облагается коммиссиями.
        </div>
        <div id="ajax-result" style="width: 100%; margin-top: 20px; font-weight: bold; color: red"></div>
    </div>
    <div>

</div>
<?php } ?>
