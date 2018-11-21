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
					var option = $('<option>',{
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