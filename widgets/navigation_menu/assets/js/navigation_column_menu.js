jQuery(function ($) {
	$('.dropdown-menu').parent().on('shown.bs.dropdown', function () {
		var menu = jQuery(this).find('.dropdown-menu');
		var position = menu.offset();
		menu.css('position', 'fixed').css(position);
	});
	$('.dropdown-menu').parent().on('hidden.bs.dropdown', function () {
		var menu = jQuery(this).find('.dropdown-menu');
		var position = menu.offset();
		menu.css('position', 'absolute').css('left', '0px').css('top', '0px');
	});
});