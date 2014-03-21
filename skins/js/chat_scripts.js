 $(document).ready(function (){
	$(window).blur(function()
	{
		// Покидаем вкладку с чатом
		$('#chat_body').attr({'alt':'not_active'});
	});

	$(window).focus(function()
	{
		// Возврат к вкладке с чатом
		$('#chat_body').attr({'alt':'active'});
	});

	// делаем фокус на поле ввода при загрузке страницы
	if ($("#chat_text_input").size()>0)
	{
		$("#chat_text_input").focus();
	}



	function send_message(){
		var message_text = $('#chat_text_input').val();
		if (message_text != ""){
			$.ajax({
				url:  document.location.href,
				type: 'POST',
				data:{'ajax-query': 'true', 'type-class':'model', 'method': 'AddMessageChat', 'message_text': message_text},
				dataType: 'html',
				success: function (result){
					$('#chat_text_input').val('');
					get_chat_messages();
				}
			});
		}
	}
     function send_message(){
         var message_text = $('#chat_text_input').val();
         if (message_text != ""){
             $.ajax({
                 url:  document.location.href,
                 type: 'POST',
                 data:{'ajax-query': 'true', 'type-class':'model', 'method': 'AddMessageChat', 'message_text': message_text},
                 dataType: 'html',
                 success: function (result){
                     $('#chat_text_input').val('');
                     get_chat_messages();
                 }
             });
         }
     }

	function get_chat_messages (){
		if ($('#block').val() == 'no')
		{
			$('#block').val('yes');
			var last_act = $('#last_act').val();
			$.ajax(
			{
				url: '/tournament/chat',
				type: 'POST',
				data: {'action': 'get_chat_message', 'last_act': last_act},
				dataType: 'html',
				success: function (result){
                    $("#load-chat").remove();
					result = $.parseJSON(result);
					$('#block').val('no');
					if (result.its_ok == 1)
					{
						$('#chat_text_field').append(result.message_code);
						$('#last_act').val(result.last_act);
						$('#chat_text_field').scrollTop($('#chat_text_field').scrollTop()+100*$('.chat_post_my, .chat_post_other').size());
						if ($('#chat_body').attr('alt') == 'not_active'){
							$("#jpId").jPlayer({
								ready: function (){
									$(this).jPlayer("setMedia",{
										mp3: '/storage/chat/sounds/new_mess.mp3'
									}).jPlayer("play");
								},
								solution: "html, flash",
								swfPath: "flash",
								supplied: "mp3",
								volume: 1
							});
							$("#jpId").jPlayer("play");
							var title_anim = setInterval(function(){
								if ($('#chat_body').attr('alt') == 'not_active'){
									setTimeout(function() { document.title = "Новое сообщение"; },2500);
								} else {
									clearInterval(title_anim);
								}
							}, 2200);
						}
                        $('#scrollbar1').tinyscrollbar({ sizethumb: 60 });
                        $('#scrollbar1').tinyscrollbar_update($('.overview').height() - $('.viewport').height());

					}

				}
			});
		}
	}

	$('#chat_text_input').keyup(function(event){
        if (event.which == 13){
            event.preventDefault();
			send_message();
    	}
    });
	setInterval(get_chat_messages, 2000);
});
	