function formatGroup(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + ($(item.element).data('logo') || '') +
		'" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' + 'style="background: ' + ($(item.element).data('typecolor') || 'inherit') +
		';color:' + ($(item.element).data('textcolor') || 'inherit') + '">' + ($(item.element).data('typename') || '')
		+ '</div></div></div>';
}

function formatGroupAJAX(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + (item.logo || '') +
		'" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' +
		'style="background: ' + (item.typecolor || 'inherit') + ';"' + '>' + item.typename || '' + '</div></div></div>';
}

function submit_toggle(select) {
	let input = jQuery(select.target).parent().find(':submit');
	if (0 < jQuery(select.target).val().length) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');

	}
}