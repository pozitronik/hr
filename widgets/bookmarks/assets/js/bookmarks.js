function add_bookmark(route, name, type) {
	jQuery.ajax({
		url: '/ajax/user-add-bookmark',
		data: {
			route: route,
			name: name,
			type: type
		},
		method: 'POST'
	}).done(function (data) {
	});
}