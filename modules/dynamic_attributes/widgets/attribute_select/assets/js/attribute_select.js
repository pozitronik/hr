function submit_toggle(select) {
	let input = jQuery(select.target).parent().find(':submit');
	if (0 < jQuery(select.target).val().length) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');

	}
}