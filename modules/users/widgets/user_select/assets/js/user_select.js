function formatUser(item) {
	return item.text;
}

function submit_toggle(select) {
	var input = jQuery(select.target).parent().find(':submit');
	if (jQuery(select.target).val().length > 0) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');
	}
}

function ajax_submit_toggle(select, button_id) {
	var input = jQuery('#' + button_id);
	if (jQuery(select.target).val().length > 0) {
		input.removeAttr('disabled');
	} else {
		input.attr('disabled', 'disabled');
	}
}

function ajax_post(postUrl, button_id, group_id) {
	var input = jQuery('#' + button_id);
	var values = input.parent().parent().find('select').val();

	if (values.length > 0) {
		input.attr('disabled', 'disabled');
		jQuery.ajax({
			url: postUrl,
			data: {
				userId: values,
				groupId: group_id
			},
			method: 'POST'
		}).done(function (data) {
			input.removeAttr('disabled');
		});
	}

}


function processResults(data, params) {//unused
	params.page = params.page || 1;
	var result = {
		results: data.results,
		pagination: {
			more: data.results.length === 20
		}
	};
	return result;
}