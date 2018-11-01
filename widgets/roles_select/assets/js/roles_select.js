function set_roles(userId, groupId, roles) {
	jQuery('#' + userId + '-roles-progress').show();
	jQuery.ajax({
		url: '/ajax/set-user-roles-in-group',
		data: {
			userid: userId,
			groupid: groupId,
			roles: roles
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + userId + '-roles-progress').hide();
	});
}