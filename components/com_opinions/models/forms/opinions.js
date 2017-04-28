(function($) {

	$(document).ready(function() {

		var lang = $('html').attr('lang');
		var loading = (lang === 'pl-pl') ? 'Wysyłanie...' : 'Vysílání...';
		var formTitle = (lang === 'pl-pl') ? 'Referencje' : 'Reference';

		$('#contact-form :input').each(function() {

			$(this).focus(function() {
				$(this).removeAttr('placeholder');
				$('.profishop-contact h3').html(formTitle);
			});
		});

		$('.submission').click(function() {

			var formData = $('#contact-form').serializeArray();
			formData.push({
				name: 'ajax',
				value: true
			});

			$.ajax({
				url: 'index.php?option=com_opinions&task=send&format=json',
				type: 'post',
				dataType: 'json',
				data: formData,
				beforeSend: function(e) {
					$(this).attr('disabled', true);
					$('.profishop-contact h3').html(loading);
				},
				success: function(e) {
					$(this).attr('disabled', false);
					if (e.success) {
						$('#contact-form input[type=text], #contact-form input[type=email], #contact-form textarea').val('');
						$('#contact-form input[type=checkbox]').attr('checked', false);
						$('.profishop-contact h3').html(e.send);
					} else {
						$('.profishop-contact h3').html(e.notsend);

						$.each(e, function(key, val) {
							$('#' + key).attr('placeholder', val);
						});

					}
				}
			});
			return false;
		});

		$('#contact-form .alert').bind('close', function() {
			$(this).hide();
			return false;
		});

	});
})(jQuery);


