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
	var color = $(item.element).data('color');
	if (color) {
		color = 'style="background: ' + color + ';"';
	}

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + color + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + color + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedGroupRelation(item) {
	var color = $(item.element).data('color');
	if (color) {
		color = 'style = "padding: 0px 5px;background: ' + color + ';"';
	}

	if ($(item.element).data('boss')) {
		return '<span ' + color + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + color + '>' + item.text + '</span>';
}