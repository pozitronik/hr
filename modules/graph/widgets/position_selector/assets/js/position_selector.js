let select = $('select[name=positions]');

/**
 * Сохраняем конфигурацию
 */
$('.js-save-position-config').on('click', function() {
	let configName = $("#position-configName").val();
	if (isEmpty(configName)) {
		$.notify('Не заполнено имя карты', {type: "warning"});
	} else {
		graphControl.saveNodesPositions(configName);

		if (0 === $("select[name=positions] option[value='" + configName + "']").length) {
			select.append(new Option(configName, configName, false, true));
		}
		select.trigger('change');


		$("#config-dialog-modal").modal("hide");

	}
});

/**
 * Применяем конфигурацию
 */
select.on('change', function() {
	let configName = $(this).val();
	if (!isEmpty(configName)) {
		graphControl.loadNodesPositions(configName);
	}
	display_deletion_item();
});
/**
 * Удаляем конфигурацию
 */
$('.js-remove-position-config').on('click', function() {
	let configName = select.val();
	if (!isEmpty(configName)) {
		graphControl.deleteNodesPositions(configName);
		$('select[name=positions] option[value="' + configName + '"]').detach();
	}
});

display_deletion_item = function() {
	if (isEmpty(select.val())) {
		_.hide('.js-remove-position-config');
	} else {
		_.show('.js-remove-position-config');
	}
};
$(function() {
	display_deletion_item();

});