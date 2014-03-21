<script type="text/javascript" >

	function closeModal(name){        
        $('#fade , #'+name).fadeOut();
        return false;
    }            
    function deleteAdvert()
    {
		
        //var arrCheck = $('input[type=checkbox]:checked');
        var arrCheck = $('.advert-check:checked');
        if(arrCheck.length > 0)
        {
            $('#showform-delete').fadeIn();
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            var popuptopmargin = ($('#showform').height() + 10) / 2;
            var popupleftmargin = ($('#showform').width() + 10) / 2;

            $('#showform-delete').css({
                'margin-top' : -popuptopmargin,
                'margin-left' : -popupleftmargin
            });
            var myCheck = new Array();
            arrCheck.each( function() {                
                myCheck += this.value + ',';                
                });
			$("#delete-hidden-value").html('<input type="hidden" name="all-chek" value="' + myCheck + '">');
            $("#num-elements-delete").html('Выбранно ' + arrCheck.length + ' объявления');
        }
        else
		{
            alert('Выберите хотя бы один элемент');
		}
	}
</script>

<div id="showform-delete" class="modal-form">
<form action="" method="post">
    <div class="modal-header">
        <img src="/skins/img/logo-modal.png">
        <b>Удаление объявлений</b>
        <img id="close-modal" onclick="closeModal('showform-delete')" src="/skins/img/close-modal.png" />
    </div>
    <div id="modalform-delete">
        <b id="num-elements-delete"></b><br />
        <br />
    </div>
    
        <div id="info-messge-modal-delete">
            <img src="/skins/img/messgae-modal.png" />
            <div>Удалить выбранные элементы, без возможности востановления?</div>
        </div>    
        <div id="butt-action-modal">
		<div id='delete-hidden-value'></div>
            <img onclick="closeModal('showform-delete')" src="/skins/img/butt-close-modal.png" />
			<input type="hidden" name="method" value="DeleteAdvert" />
            <input style="position:relative; bottom:18px; width:90px;" type='submit' value="Удалить" />
        </div>  
</form>		
</div>
<div id="fade"></div>
