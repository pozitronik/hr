/**
 * Сохраняем конфигурацию
 */
$('.js-save-position-config').on('click', function() {
	var configName = $("#position-configName").val();
	if (isEmpty(configName)) {
		$.notify('Не заполнено имя карты', {type: "warning"});
	} else {
		save_nodes_positions(configName);
		$('select[name=positions]').append(new Option(configName, configName, false, true)).trigger('change');
		$("#config-dialog-modal").modal("hide");

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
	display_deletion_item();
});
/**
 * Удаляем конфигурацию
 */
$('.js-remove-position-config').on('click', function() {
	var configName = $(this).val();
	if (!isEmpty(configName)) {
		delete_nodes_positions(configName);
	}
});

display_deletion_item = function() {
	if (!isEmpty($('select[name=positions]').val())) {
		_.show('.js-remove-position-config');
	} else {
		_.hide('.js-remove-position-config');
	}

}

$(function() {
	display_deletion_item();

});