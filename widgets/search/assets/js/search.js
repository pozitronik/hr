function open_result(item) {
	let url;
	switch (item.type) {
		case 'user':
			// url = '/users/users/profile?id=' + item.id;
			url = false;
			break;
		case 'group':
			url = '/groups/groups/profile?id=' + item.id;
			break;
	}
	if (url) window.open(url, '_blank');
}