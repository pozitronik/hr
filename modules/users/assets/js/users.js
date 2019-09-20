function set_option(key, value) {
	jQuery.ajax({
		url: '/users/ajax/user-set-bookmark',
		data: {
			key: key,
			value: value,
		},
		method: 'POST'
	}).done(function (data) {

	});
}