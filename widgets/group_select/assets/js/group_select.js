function formatGroup(item) {
	if (item.loading) {
		return item.text;
	}
	var typecolor = $(item.element).data('typecolor');
	if (typecolor) {
		typecolor = 'style="background: ' + typecolor + ';"';
	}
	var logo = $(item.element).data('logo');
	var typename = $(item.element).data('typename');
	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + logo + '" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' + typecolor + '>' + typename + '</div></div></div>';
}