function formatUserRight(item) {
	if (item.loading) {
		return item.text;
	}
	var description = $(item.element).data('description')||'';
	return '<div class="select-item"><div class="row"><div class="col-sm-2">' + item.text + '</div><div class="col-sm-8 text-overflow">' + description+ '</div></div></div>';
}