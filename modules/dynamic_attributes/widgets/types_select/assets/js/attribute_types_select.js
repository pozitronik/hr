function set_roles(userId, attributeId, roles) {
	jQuery('#' + userId + '-' + attributeId + '-types-progress').show();
	jQuery.ajax({
		url: '/ajax/set-attribute-types-for-user',
		data: {
			userId: userId,
			attributeId: attributeId,
			roles: roles
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + userId + '-' + attributeId + '-types-progress').hide();
	});
}

function formatType(item) {
	if (item.loading) {
		return item.text;
	}
	var color = $(item.element).data('color');
	if (color) {
		color = 'style="background: ' + color + ';"';
	}

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + color + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + color + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedType(item) {
	var color = $(item.element).data('color');
	if (color) {
		color = 'style = "padding: 0px 5px;background: ' + color + ';"';
	}

	if ($(item.element).data('boss')) {
		return '<span ' + color + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + color + '>' + item.text + '</span>';
}