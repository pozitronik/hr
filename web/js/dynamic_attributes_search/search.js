function attribute_changed(element) {
	$.ajax({
		type: 'POST',
		url: '/ajax/attribute-get-properties',
		data: {
			attribute: element.val()
		},
		success: function (data) {
			if (0 === data.result) {
				var Index = element.data('index');

				var propertySelect = $('*[data-tag="search-property"][data-index="' + Index + '"]');
				propertySelect.empty();
				for (var key in data.items) {
					var option = $('<option>', {
						value: data.items[key].id,
						text: data.items[key].name,
						'data-type': data.items[key].type
					});
					propertySelect.append(option);
				}
				property_changed(propertySelect);
			}
		}
	});
}

function property_changed(element) {
	$.ajax({
		type: 'POST',
		url: '/ajax/attribute-get-property-condition',
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
