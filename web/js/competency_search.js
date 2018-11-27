function competency_changed(element){
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-fields',
		data: {
			competency: element.val()
		},
		success: function (data) {
			if (0 === data.result) {
				var conditionIndex = element.val();

				var fieldSelect = $('*[data-tag="search-field"]');
				fieldSelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: data.items[key].id,
						text: data.items[key].name,
						'data-type': data.items[key].type
					});
					fieldSelect.append(option);
				}
				field_changed(fieldSelect);
			}
		}
	});
}

function field_changed(element){
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-field-condition',
		data: {
			type: element.find(':selected').data('type')
		},
		success: function (data) {
			if (0 === data.result) {
				var fieldIndex = element.data('field');
				var conditionSelect =  $('*[data-tag="search-condition"]');
				conditionSelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: key,
						text: data.items[key]
					});

					conditionSelect.append(option);
				}
			}
		}
	});
}

function duplicate_condition(index) {
	var initialRow = $('.row[data-index="'+index+'"]');
	var newRow = $(initialRow.parent().html());

	initialRow.after(newRow);

}