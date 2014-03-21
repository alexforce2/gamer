<?php $balance = (int) $_SESSION['user-data']['balance']; ?>
<script type="text/javascript" >        
    function activateUpAdvert()
    {
        debugger;
        var arrCheck = $('.advert-check:checked');
        var arrCheckParam = $('.check-public:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-activate').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform-activate').height() + 10) / 2;
            var popupleftmargin = ($('#showform-activate').width() + 10) / 2;

            $('#showform-activate').css({
                'margin-top' : -popuptopmargin,
                'margin-left' : -popupleftmargin
            });
            var myCheck = new Array();
            arrCheck.each( function() {                
                myCheck += this.value + ',';                
            });
            var myCheckParam = new Array();
            arrCheckParam.each( function() {                
                myCheckParam += this.value + ',';                
            });
            $("#activate-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#activate-hidden-value").append('<input type="hidden" name="param-chek" value="' + myCheckParam + '">');

            $("#num-elements-activate").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
            alert('Выберите хотя бы один элемент, если параметры не будут заданы, то объявление автоматически будет размещенно на всех ресурсах.');
        
    }    
    function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }
    
</script>
<style>#showform-activate{height: 180px}</style>
<div id="showform-activate">
    <form action="" method="post">        
        <div class="modal-header">
            <img src="/skins/img/logo-modal.png">
            <b>Окно сообщения</b>
            <img id="close-modal" onclick="closeModal('showform-activate')" src="/skins/img/close-modal.png" />
        </div>
        <?php if($_SESSION['blocked-advert'] == 0){ ?>
        <div id="modalform">
            Вы действительно хотите активировать выбранные объявления?<br /><br />
            <b id="num-elements-activate"></b><br />
            Текущий баланс - <b><?= $_SESSION['user-data']['balance']; ?> руб.</b><br />
        </div>    
        <div id="butt-action-modal-activate" class="butt-action-modal">
            <img onclick="closeModal('showform-activate')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="activate-hidden-value"></div>
            <input type="hidden" name="method" value="ActivateAdvert" />
            <div id="button-change-modal-activate">
                <input style="position:relative; top: 12px; width:90px;" type='submit' value="Активировать" />
            </div>
        </div>
        <? }else{ ?>        
        <div id="modalform">
            Лимит ваших объявлений исчерпан, либо текущий тариф истек<br /><br />            
        </div>    
        <div id="butt-action-modal-activate" class="butt-action-modal">
            <img onclick="closeModal('showform-activate')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="activate-hidden-value"></div>
        </div>
        <?}?>
    </form>
</div>
<div id="fade"></div>
