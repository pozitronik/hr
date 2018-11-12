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
		if (0 === data.result) {
			$('[name=add-bookmark]').hide('slow');
			$("<li hidden='hidden'><a href='" + route + "'>" + name + "</a></li>").appendTo('#nav-bookmarks').show('slow');;
		}

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
		if (0 === data.result) {
			$('[name=remove-bookmark]').hide();
			$('li[data-href="' + route + '"]').hide('slow', function(){
				$('li[data-href="' + route + '"]').remove();
			})
		}

	});
}