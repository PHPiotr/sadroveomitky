(function($){
	$(document).ready(function(){
		
		$('.photo').click(function(){
			if($(this).hasClass('normal')){
				$('.main').addClass('normal').removeClass('main');
				$('#jform_mainphoto').val($(this).attr('alt'));
				$(this).removeClass('normal').addClass('main');
			}else{
				$(this).removeClass('main').addClass('normal');
			}
		});
		
		$('.remove').change(function(){
			if(!confirm('Na pewno usunąć to zdjęcie?')){
				$(this).attr('checked', false);
				return false;
			}		
			$(this).parent('div.photos').hide();
			$.ajax({
				url: 'index.php?option=com_gals&task=gal.remove',
				type: 'post',
				data: {
					photo_id: $(this).attr('id'),
					id: $('input#id').val(),
					photo: $(this).val(),
					main: $(this).prev('img').hasClass('main') ? 1 : 0			
				}
			});
		});
		
	});
})(jQuery);

