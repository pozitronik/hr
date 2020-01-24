/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function templateResult(item) {
	return item.text;
}

/**
 * Форматирование элемента списка при AJAX-запросе
 * @param item
 * @returns {*}
 */
function templateResultAJAX(item) {
	return item.text;
}


/**
 * Форматирование <не помню для чего>
 * @param markup
 * @returns {*}
 */
function escapeMarkup(markup) {
	return markup;
}
