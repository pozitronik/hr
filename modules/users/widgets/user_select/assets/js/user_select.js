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

function ajax_post(postUrl, postValue, progressId) {
	jQuery(progressId).show();
	jQuery.ajax({
		url: postUrl,
		data: postValue,
		method: 'POST'
	}).done(function (data) {
		jQuery(progressId).hide();
	});
}
