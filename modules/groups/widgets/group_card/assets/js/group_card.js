(function($, sr) {//регистрирует событие smartresize на любой ресайз окна
	var debounce = function(func, threshold, execAsap) {
		let timeout;
		return function debounced() {
			let obj = this,
				args = arguments;

			function delayed() {
				if (!execAsap) func.apply(obj, args);
				timeout = null;
			}
			if (timeout) clearTimeout(timeout);
			else if (execAsap) func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 300);
		};
	};
	// smartresize
	jQuery.fn[sr] = function(fn) {
		return fn?this.bind('resize', debounce(fn)):this.trigger(sr);
	};

})(jQuery, 'smartresize');


// usage:
// $(window).smartresize(function() {
// 	if ('undefined' !== typeof (Msnry)) Msnry.layout();
// });

function changeIcon(element) {
	let i = element.find('i');
	let parentPanelSmall = element.parents('.panel-card-small');
	if ('true' === element.attr('aria-expanded')) {
		i.removeClass('fa-window-minimize').addClass('fa-window-maximize');
		parentPanelSmall.removeClass('expanded');
	} else {
		i.removeClass('fa-window-maximize').addClass('fa-window-minimize');
		parentPanelSmall.addClass('expanded');
	}
}