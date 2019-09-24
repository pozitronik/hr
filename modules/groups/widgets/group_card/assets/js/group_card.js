function normalize_widths() {
	let containerWidth = $('.grid').width(),
		cardBaseWidth = containerWidth / 5;
	$('.panel-card').each(function(index) {
		let cardWidth = $(this).width();
		let cardSizeMultiplier = Math.ceil(cardWidth / cardBaseWidth);
		$(this).addClass('grid-item--width' + cardSizeMultiplier);
	});
}


(function($, sr) {
	var debounce = function(func, threshold, execAsap) {
		let timeout;
		return function debounced() {
			let obj = this,
				args = arguments;

			function delayed() {
				if (!execAsap) func.apply(obj, args);
				timeout = null;
			};

			if (timeout) clearTimeout(timeout);
			else if (execAsap) func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 300);
		};
	}
	// smartresize
	jQuery.fn[sr] = function(fn) {
		return fn?this.bind('resize', debounce(fn)):this.trigger(sr);
	};

})(jQuery, 'smartresize');


// usage:
$(window).smartresize(function() {
	if ('undefined' !== typeof (Msnry)) Msnry.layout();
});

function changeIcon(element) {
	let i = element.find('i');
	if ('true' === element.attr('aria-expanded')) {
		i.removeClass('fa-window-minimize').addClass('fa-window-maximize')
	} else {
		i.removeClass('fa-window-maximize').addClass('fa-window-minimize')
	}
}