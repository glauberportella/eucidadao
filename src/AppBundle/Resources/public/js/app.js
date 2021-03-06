$(function() {
	// jQuery Mask Plugin
	var maskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	};
	var options = {
		onKeyPress: function(val, e, field, options) {
			field.mask(maskBehavior.apply({}, arguments), options);
		}
	};

	$('.phone').mask(maskBehavior, options);
	$('.date').mask('00/00/0000');
})
