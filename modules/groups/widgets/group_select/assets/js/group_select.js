/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function GroupsTemplateResult(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + ($(item.element).data('logo') || '') +
		'" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' + 'style="background: ' + ($(item.element).data('typecolor') || 'inherit') +
		';color:' + ($(item.element).data('textcolor') || 'inherit') + '">' + ($(item.element).data('typename') || '')
		+ '</div></div></div>';
}

/**
 * Форматирование элемента списка при AJAX-запросе
 * @param item
 * @returns {*}
 */
function GroupsTemplateResultAJAX(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item"><div class="row"><div class="col-sm-8"><img src="' + (item.logo || '') +
		'" class="group-logo" alt="logo">' + item.text + '</div><div class="col-sm-4 text-overflow" ' +
		'style="background: ' + (item.typecolor || 'inherit') + ';"' + '>' + item.typename || '' + '</div></div></div>';
}

/**
 * Форматирование <не помню для чего>
 * @param markup
 * @returns {*}
 */
function GroupsEscapeMarkup(markup) {
	return markup;
}