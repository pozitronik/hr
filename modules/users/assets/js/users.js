function set_option(key, value) {
	jQuery.ajax({
		url: '/users/ajax/user-set-option',
		data: {
			key: key,
			value: value,
		},
		method: 'POST'
	}).done(function (data) {
		console.log(data);
	});
}