function set_roles(userId, groupId, roles) {
	jQuery('#' + userId + '-' + groupId + '-roles-progress').show();
	jQuery.ajax({
		url: '/ajax/set-user-roles-in-group',
		data: {
			userId: userId,
			groupId: groupId,
			roles: roles
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + userId + '-' + groupId + '-roles-progress').hide();
	});
}

function formatRole(item) {
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

function formatSelectedRole(item) {
	var color = $(item.element).data('color');
	if (color) {
		color = 'style = "padding: 0px 5px;background: ' + color + ';"';
	}

	if ($(item.element).data('boss')) {
		return '<span ' + color + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + color + '>' + item.text + '</span>';
}