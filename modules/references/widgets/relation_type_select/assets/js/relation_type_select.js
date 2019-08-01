function set_group_relation_type(parentGroupId, childGroupId, relation) {
	jQuery('#' + parentGroupId + '-' + childGroupId + '-relation-progress').show();
	jQuery.ajax({
		url: '/references/ajax/set-group-relation-type',
		data: {
			parentGroupId: parentGroupId,
			childGroupId: childGroupId,
			relation: relation
		},
		method: 'POST'
	}).done(function (data) {
		jQuery('#' + parentGroupId + '-' + childGroupId + '-relation-progress').hide();
	});
}

function formatGroupRelation(item) {
	if (item.loading) {
		return item.text;
	}
	let style = 'style="background: ' + ($(item.element).data('color') || 'inherit') + '; color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedGroupRelation(item) {
	let style = 'style = "padding: 0px 5px;background: ' + ($(item.element).data('color') || 'inherit') + ';color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {
		return '<span ' + style + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + style + '>' + item.text + '</span>';
}