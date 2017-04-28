(function($) {
	$(function() {

		var thumb_id, main_id, iframe_src, new_src;

		$('.you .jcarousel a.thumb').click(function() {

			thumb_id = $(this).attr('id');
			main_id = $('.you .carousel iframe').attr('class');
			iframe_src = $('.you .carousel iframe').attr('src');
			new_src = iframe_src.replace(main_id, thumb_id);

			$('.you .carousel iframe').attr('src', new_src);
			$('.you .carousel iframe').attr('class', thumb_id);

			return false;
		});

		$('.jcarousel').jcarousel({
			wrap: 'circular'
		});

		$('.jcarousel-control-prev')
				.jcarouselControl({
					target: '-=1'
				});

		$('.jcarousel-control-next')
				.jcarouselControl({
					target: '+=1'
				});

	});
})(jQuery);

