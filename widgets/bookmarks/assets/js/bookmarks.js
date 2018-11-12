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

function remove_bookmark(route) {
	jQuery.ajax({
		url: '/ajax/user-remove-bookmark',
		data: {
			route: route,
		},
		method: 'POST'
	}).done(function (data) {
	});
}