function open_result(item) {
	let url;
	switch (item.type) {
		case 'user':
			url = '/users/users/profile?id=' + item.id;
			break;
		case 'group':
			url = 'users?UsersSearch[groupId]=' + item.id;
			break;
	}
	window.open(url, '_blank');
}