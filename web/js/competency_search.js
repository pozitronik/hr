function competency_changed(element) {
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-fields',
		data: {
			competency: element.val()
		},
		success: function (data) {
			if (0 === data.result) {
				var Index = element.data('index');

				var fieldSelect = $('*[data-tag="search-field"][data-index="' + Index + '"]');
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

function field_changed(element) {
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-field-condition',
		data: {
			type: element.find(':selected').data('type')
		},
		success: function (data) {
			if (0 === data.result) {
				var Index = element.data('index');
				var conditionSelect = $('*[data-tag="search-condition"][data-index="' + Index + '"]');
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
