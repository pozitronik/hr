function submit_toggle(select) {
	var input = jQuery(select.target).parent().find(':submit');
	if (jQuery(select.target).val().length > 0) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');

	}
}