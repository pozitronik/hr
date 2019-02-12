function formatGroup(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}
	var typecolor = $(item.element).data('typecolor');
	if (typecolor) typecolor = 'style="background: ' + typecolor + ';"';
	var logo = $(item.element).data('logo');
	var typename = $(item.element).data('typename');
	if (!typename) typename = '';
	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + logo + '" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' + typecolor + '>' + typename + '</div></div></div>';
}

function submit_toggle(select) {
	var input = jQuery(select.target).parent().find(':submit');
	if (jQuery(select.target).val().length > 0) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');

	}
}