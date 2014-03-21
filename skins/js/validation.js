/*
*	ВАЛИДАЦИЯ
*/
function _validation() {
	var elements = {
		city: {
			type: function(val) {
				var message = '';
				if (!val || val == '') {
					var message = 'Поле не должно быть пустым';
				}
				return message;
			},
			value: $('#city').val(),
			onError: function(obj, text) {
				$('#' + obj).closest('tr').find('.tooltip').addClass('error').html(text);;
			},
			onSuccess: function(obj) {
				$('#' + obj).closest('tr').find('.tooltip').removeClass('error');
			}
		},
	}

	var error = false;

	$.each(elements, function(item, val) {
		var obj 	 = elements[item],
			element  = $('input[id="'+ item +'"]'),
			is_valid = element.attr('data-type'); // ЕСЛИ У ЭЛЕМЕНТА ЕСТЬ ПАРАМЕТР data-type ТО ПРОВЕРЯЕМ ЕГО НА ВАЛИДНОСТЬ

		if ( is_valid && $.isFunction(obj.type)) {
			message = obj.type.call(this, obj.value); // СООБЩЕНИЕ ОБ ОШИБКЕ
			if ( typeof(message) != 'undefined' && message != '' ) {
				error = true;
				obj.onError.call(this, item, message);
			} else {
				obj.onSuccess.call(this, item);
			}
		}
	});

	error = (error == false) ? true : false

	return error;
}