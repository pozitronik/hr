/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function UsersTemplateResult(item) {
	return item.text;
}

/**
 * Форматирование элемента списка при AJAX-запросе
 * @param item
 * @returns {*}
 */
function UsersTemplateResultAJAX(item) {
	return item.text;
}


/**
 * Форматирование <не помню для чего>
 * @param markup
 * @returns {*}
 */
function UsersEscapeMarkup(markup) {
	return markup;
}