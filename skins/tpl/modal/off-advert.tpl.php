<?php $balance = (int) $_SESSION['user-data']['balance'];?>
<script type="text/javascript" >        
    function offAdvert()
    {
        debugger;
        var arrCheck = $('.advert-check:checked');
        var arrCheckParam = $('.check-public:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-off-advert').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform-off-advert').height() + 10) / 2;
            var popupleftmargin = ($('#showform-off-advert').width() + 10) / 2;

            $('#showform-off-advert').css({
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
            $("#off-advert-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#off-advert-hidden-value").append('<input type="hidden" name="param-chek" value="' + myCheckParam + '">');

            $("#num-elements-off-advert").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
            alert('Выберите хотя бы один элемент, если параметры не будут заданы, то объявление автоматически будет размещенно на всех ресурсах.');
        
    }    
    function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }
    
</script>
<style>#showform-off-advert{height: 200px}</style>
<div id="showform-off-advert">
    <form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Окно сообщения</b>
        <img id="close-modal" onclick="closeModal('showform-off-advert')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform">
        Вы действительно хотите деактивировать выбранные объявления?<br /><br />
        <b id="num-elements-activate"></b><br />
        Текущий баланс - <b><?= $_SESSION['user-data']['balance']; ?> руб.</b><br />
    </div>    
        <div id="butt-action-modal-activate" class="butt-action-modal">
            <img onclick="closeModal('showform-activate')" class="FL" src="/skins/img/butt-close-modal.png" />            
            <div id="off-advert-hidden-value"></div>
            <input type="hidden" name="method" value="OffAdvert" />
            <div id="button-change-modal-off-advert">
                <input style="position:relative; top: 12px; width:90px;" type='submit' value="Деактивировать" />
            </div>
        </div>
        
    </form>
</div>
<div id="fade"></div>
