/**
 * Сохраняем пользовательский фильтр
 */
$('.js-save-user-position-config').on('click', function () {
	var configName = $("#position-configName").val();
	if (isEmpty(configName)) {
		$.notify('Не заполнено имя карты', {type: "warning"});
	} else {
		save_nodes_positions(configName);
	}
});