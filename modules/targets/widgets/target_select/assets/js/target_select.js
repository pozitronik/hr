/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function TargetsTemplateResult(item) {
	return item.text;
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