function set_types(userId, attributeId, types) {
	let csrfParam = jQuery('meta[name="csrf-param"]').attr("content");
	let csrfToken = jQuery('meta[name="csrf-token"]').attr("content");
	jQuery('#' + userId + '-' + attributeId + '-types-progress').show();
	jQuery.ajax({
		url: '/attributes/ajax/set-attribute-types-for-user',
		data: {
			userId: userId,
			attributeId: attributeId,
			types: types,
			csrfParam : csrfToken
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
	let style = 'style="background: ' + ($(item.element).data('color') || 'inherit') + '; color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {

		return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-1"><i class="fa fa-crown"></i></div><div class="col-sm-11">' + item.text + '</div></div></div>';
	}
	return '<div class="select-item" ' + style + '"><div class="row"><div class="col-sm-11">' + item.text + '</div></div></div>';
}

function formatSelectedType(item) {
	let style = 'style="padding: 0px 5px; background: ' + ($(item.element).data('color') || 'inherit') + '; color: ' + ($(item.element).data('textcolor') || 'inherit') + '"';

	if ($(item.element).data('boss')) {
		return '<span ' + style + '><i class="fa fa-crown"></i> ' + item.text + '</span>';
	}
	return '<span ' + style + '>' + item.text + '</span>';
}