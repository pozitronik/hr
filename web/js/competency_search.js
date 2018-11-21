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
					fieldSelect.append("<option value='" + data.items[key].id + "'>" + data.items[key].name + "</option>");
				}
			}


		}
	});
});