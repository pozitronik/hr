/**
 * Форматирование элемента списка по умолчанию
 * @param item
 * @returns {*}
 */
function DynamicAttributesTemplateResult(item) {
	return item.text;
}

/**
 * Форматирование элемента списка при AJAX-запросе
 * @param item
 * @returns {*}
 */
function DynamicAttributesTemplateResultAJAX(item) {
	return item.text;
}


/**
 * Форматирование <не помню для чего>
 * @param markup
 * @returns {*}
 */
function DynamicAttributesEscapeMarkup(markup) {
	return markup;
}