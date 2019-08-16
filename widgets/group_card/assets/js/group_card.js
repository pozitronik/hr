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
			type: '.group-type-name',
			// weight: function(itemElem) {
			// 	var weight = $('.weight').text();
			// 	return parseFloat(weight.replace(/[\(\)]/g, ''));
			// }
		}
	});

// bind sort button click
	$('.sort-by-type').bind('click', function(event) {
		Iso.arrange({sortBy: 'type'});
		// Msnry.layout();
	});

// change is-checked class on buttons
	var buttonGroups = document.querySelectorAll('.button-group');
	for (var i = 0; i < buttonGroups.length; i++) {
		buttonGroups[i].addEventListener('click', onButtonGroupClick);
	}

	function onButtonGroupClick(event) {
		// only button clicks
		if (!matchesSelector(event.target, '.button')) {
			return;
		}
		var button = event.target;
		button.parentNode.querySelector('.is-checked').classList.remove('is-checked');
		button.classList.add('is-checked');
	}

}