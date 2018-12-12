function set_roles(userId, groupId, roles) {
	jQuery('#' + userId + '-' + groupId + '-roles-progress').show();
	jQuery.ajax({
		url: '/ajax/set-user-roles-in-group',
		data: {
			userid: userId,
			groupid: groupId,
			roles: roles
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + userId + '-' + groupId + '-roles-progress').hide();
	});
}

function formatItem(item) {
	if (item.loading) {
		return item.text;
	}
	var markup =
		'<div class="row">' +
		'<div class="col-sm-5">' +
		'<b style="margin-left:5px">' + item.text + '</b>' +
		'</div>' +
		'<div class="col-sm-3"><i class="fa fa-crown"></i> ' + item.desc + '</div>' +
		'</div>';
	return '<div style="overflow:hidden;">' + markup + '</div>';
};

function formatSelectedItem(item) {
	return '<div class="col-sm-3"><i class="fa fa-crown"></i> ' + item.text + '</div>';
	// return item.text;
}