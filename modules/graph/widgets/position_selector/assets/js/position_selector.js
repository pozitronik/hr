/**
 * Сохраняем конфигурацию
 */
$('.js-save-position-config').on('click', function() {
	let configName = $("#position-configName").val();
	if (isEmpty(configName)) {
		$.notify('Не заполнено имя карты', {type: "warning"});
	} else {
		graphControl.saveNodesPositions(configName);
		$('select[name=positions]').append(new Option(configName, configName, false, true)).trigger('change');
		$("#config-dialog-modal").modal("hide");

	}
});

/**
 * Применяем конфигурацию
 */
$('select[name=positions]').on('change', function() {
	let configName = $(this).val();
	if (!isEmpty(configName)) {
		graphControl.loadNodesPositions(null, configName);
	}
	display_deletion_item();
});
/**
 * Удаляем конфигурацию
 */
$('.js-remove-position-config').on('click', function() {
	let configName = $('select[name=positions]').val();
	if (!isEmpty(configName)) {
		graphControl.deleteNodesPositions(configName);
		$('select[name=positions] option[value="' + configName + '"]').detach();
	}
});

display_deletion_item = function() {
	if (isEmpty($('select[name=positions]').val())) {
		_.hide('.js-remove-position-config');
	} else {
		_.show('.js-remove-position-config');
	}
};
$(function() {
	display_deletion_item();

});