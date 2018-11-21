$(document).on('change', '#prototypecompetenciessearch-competency', function () {
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-fields',
		data: {
			competency: $(this).val()
		},
		success: function (data) {
			if (0 === data.result) {
				var fieldSelect = $('#prototypecompetenciessearch-field');
				fieldSelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: data.items[key].id,
						text: data.items[key].name,
						'data-type': data.items[key].type
					});

					fieldSelect.append(option);
				}
			}
		}
	});
});

$(document).on('change', '#prototypecompetenciessearch-field', function () {
	$.ajax({
		type: 'POST',
		url: '/ajax/competency-get-field-condition',
		data: {
			type: $(this).find(':selected').data('type')
		},
		success: function (data) {
			if (0 === data.result) {
				var conditionSelect = $('#prototypecompetenciessearch-condition');
				conditionSelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: data.items['key'],
						text: key
					});

					conditionSelect.append(option);
				}
			}
		}
	});
});