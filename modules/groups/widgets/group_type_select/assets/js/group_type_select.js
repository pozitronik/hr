function set_group_type(groupId, type) {
	jQuery('#' + groupId + '-type-progress').show();
	jQuery.ajax({
		url: '/references/ajax/set-group-type',
		data: {
			groupId: groupId,
			type: type
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + groupId + '-type-progress').hide();
	});
}

function formatGroupType(item) {
	if (item.loading) {
		return item.text;
	}
	let style = 'style="background: ' + ($(item.element).data('color') || 'inherit') + '; color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedGroupType(item) {
	let style = 'style="padding: 0px 5px; background: ' + ($(item.element).data('color') || 'inherit') + '; color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {
		return '<span ' + style + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + style + '>' + item.text + '</span>';
}