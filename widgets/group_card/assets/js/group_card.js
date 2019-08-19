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
	Msnry.layout();
});


function init_isotope() {
	Iso = new Isotope('.grid', {
		itemSelector: '.panel-card', layoutMode: 'fitRows', getSortData: {
			count: '.count parseInt',
			vacancyCount: '.vacancy-count parseInt',
			type: '.group-type-name',
			// weight: function(itemElem) {
			// 	var weight = $('.weight').text();
			// 	return parseFloat(weight.replace(/[\(\)]/g, ''));
			// }
		}
	});

	$('#sort-by-type').bind('click', function(event) {/*todo: move to widget actions*/
		if ($(this).is('.ascending')) {
			Iso.arrange({sortBy: 'type', sortAscending: false});
			$(this).removeClass('ascending');
		} else {
			$(this).addClass('ascending');
			Iso.arrange({sortBy: 'type', sortAscending: true});
		}


	});
	$('#sort-by-count').bind('click', function(event) {
		if ($(this).is('.ascending')) {
			Iso.arrange({sortBy: 'count', sortAscending: false});
			$(this).removeClass('ascending');
		} else {
			$(this).addClass('ascending');
			Iso.arrange({sortBy: 'count', sortAscending: true});
		}


	});
	$('#sort-by-vacancy').bind('click', function(event) {
		if ($(this).is('.ascending')) {
			Iso.arrange({sortBy: 'vacancy', sortAscending: false});
			$(this).removeClass('ascending');
		} else {
			$(this).addClass('ascending');
			Iso.arrange({sortBy: 'vacancy', sortAscending: true});
		}
	});

	$('#filter-chapter').bind('click', function(event) {
		if ($(this).is(':checked')) {
			Iso.arrange({filter: "[data-type='1']"});
		} else {
			Iso.arrange({filter: "*"});
		}
	});
}