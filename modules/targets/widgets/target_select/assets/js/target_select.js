/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function TargetsTemplateResult(item) {
	if (item.loading || item.hasOwnProperty('children')) {
		return item.text;
	}

	return '<div class="select-item">' +
			'<div class="row" style="padding: 0 10px;">' +
				'<div class="col-sm-8">' + item.text +'</div>' +
					'<div class="col-sm-4 text-overflow" ' + 'style="background: ' + ($(item.element).data('typecolor') || 'inherit') + ';color:' + ($(item.element).data('textcolor') || 'inherit') + '">' + ($(item.element).data('typename') || '')+ '</div>' +
				'</div>' +
			'</div>' +
		'</div>' ;
}

/**
 * Форматирование элемента списка при AJAX-запросе
 * @param item
 * @returns {*}
 */
function TargetsTemplateResultAJAX(item) {
	return item.text;
}


/**
 * Форматирование <не помню для чего>
 * @param markup
 * @returns {*}
 */
function TargetsEscapeMarkup(markup) {
	return markup;
}