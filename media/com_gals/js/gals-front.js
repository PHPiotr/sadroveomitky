(function($) {

	var connector = function(itemNavigation, carouselStage) {
		return carouselStage.jcarousel('items').eq(itemNavigation.index());
	};

	$(function() {

		$('.gallery-title').text($('.carousel-stage li a').eq(0).attr('title'));

		$('.connected-carousels .stage .prev-stage,.connected-carousels .stage .next-stage').each(function() {
			$(this).mouseover(function() {
				$(this).css('opacity', '1');
			}).mouseout(function() {
				$(this).css('opacity', '0.7');
			});
		});

		var carouselStage = $('.carousel-stage').jcarousel({wrap: 'circular'});
		var carouselNavigation = $('.carousel-navigation').jcarousel({wrap: 'circular'});

		carouselStage.on('jcarousel:visiblein', 'li', function(event, carousel) {
			var current = $(this).find('a').attr('title');
			$('.gallery-title').text(current);
		});

		carouselNavigation.jcarousel('items').each(function() {
			var item = $(this);
			var target = connector(item, carouselStage);
			item
					.on('jcarouselcontrol:active', function() {
						carouselNavigation.jcarousel('scrollIntoView', this);
						item.addClass('active');
					})
					.on('jcarouselcontrol:inactive', function() {
						item.removeClass('active');
					})
					.jcarouselControl({
						target: target,
						carousel: carouselStage
					});
		});

		$('.prev-stage')
				.on('jcarouselcontrol:inactive', function() {
					$(this).addClass('inactive');
				})
				.on('jcarouselcontrol:active', function() {
					$(this).removeClass('inactive');
				})
				.jcarouselControl({
					target: '-=1'
				});

		$('.next-stage')
				.on('jcarouselcontrol:inactive', function() {
					$(this).addClass('inactive');
				})
				.on('jcarouselcontrol:active', function() {
					$(this).removeClass('inactive');
				})
				.jcarouselControl({
					target: '+=1'
				});

		$('.prev-navigation')
				.on('jcarouselcontrol:inactive', function() {
					$(this).addClass('inactive');
				})
				.on('jcarouselcontrol:active', function() {
					$(this).removeClass('inactive');
				})
				.jcarouselControl({
					target: '-=1'
				});

		$('.next-navigation')
				.on('jcarouselcontrol:inactive', function() {
					$(this).addClass('inactive');
				})
				.on('jcarouselcontrol:active', function() {
					$(this).removeClass('inactive');
				})
				.jcarouselControl({
					target: '+=1'
				});
	});
})(jQuery);

