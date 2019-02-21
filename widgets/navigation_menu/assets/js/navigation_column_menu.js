jQuery(function ($) {
	$('table.table * .dropdown-menu').parent().on('shown.bs.dropdown', function () {
		var menu = jQuery(this).find('.dropdown-menu');
		var position = menu.offset();
		position.top -= document.documentElement.scrollTop;
		position.left -= document.documentElement.scrollLeft;
		menu.css('position', 'fixed').css(position);
	});
	$('table.table * .dropdown-menu').parent().on('hidden.bs.dropdown', function () {
		var menu = jQuery(this).find('.dropdown-menu');
		var position = menu.offset();
		menu.css('position', 'absolute').css('left', '0px').css('top', '0px');
	});
});