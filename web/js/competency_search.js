function competency_changed(element){
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-fields',
		data: {
			competency: element.val()
		},
		success: function (data) {
			if (0 === data.result) {
				var conditionIndex = element.data('competency');

				var fieldSelect = $('*[data-field="'+conditionIndex+'"]');
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
				var conditionSelect =  $('*[data-condition="'+fieldIndex+'"]');
				conditionSelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: key,
						text: key
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