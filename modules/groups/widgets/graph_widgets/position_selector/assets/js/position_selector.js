/**
 * Сохраняем конфигурацию
 */
$('.js-save-position-config').on('click', function() {
	var configName = $("#position-configName").val();
	if (isEmpty(configName)) {
		$.notify('Не заполнено имя карты', {type: "warning"});
	} else {
		save_nodes_positions(configName);
	}
});

/**
 * Применяем конфигурацию
 */
$('select[name=positions]').on('change', function() {
	var configName = $(this).val();
	if (!isEmpty(configName)) {
		load_nodes_positions(null, configName);
	}
});
/**
 * Удаляем конфигурацию
 */
$('.js-remove-position-config').on('click', function() {
	var configName = $(this).val();
	if (!isEmpty(configName)) {
		load_nodes_positions(null, configName);
	}
});
/**
 * Редактируем конфигурацию
 */
$('.js-edit-position-config').on('click', function() {

});
$('.js-edit-filter').on('click', function() {
	var filterModal = $('#edit_filters_modal');
	filterModal.modal('show');
	filterModal.find("input[name=filterName]").val($("select[name=filters_list] option:selected").text());
});