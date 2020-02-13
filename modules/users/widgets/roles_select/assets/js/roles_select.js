function set_roles(userId, groupId, roles) {
	jQuery('#' + userId + '-' + groupId + '-roles-progress').show();
	jQuery.ajax({
		url: '/references/ajax/set-user-roles-in-group',
		data: {
			userId: userId,
			groupId: groupId,
			roles: roles
		},
		method: 'POST'
	}).done(function(data) {
		jQuery('#' + userId + '-' + groupId + '-roles-progress').hide();
	});
}

function formatRole(item) {
	if (item.loading) {
		return item.text;
	}
	let style = 'style="background: ' + ($(item.element).data('color') || 'inherit') + ';color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedRole(item) {
	let style = "style = 'padding: 0px 5px;background:" + ($(item.element).data('color') || "inherit") + ";color:" + ($(item.element).data('textcolor') || "inherit")+"'";

	if ($(item.element).data('boss')) {
		return '<span ' + style + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + style + '>' + item.text + '</span>';
}