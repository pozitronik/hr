function init_sigma(id) {
	s = new sigma({
		renderer: {
			container: document.getElementById('sigma-container'),
			type: 'canvas'
		},
		settings: {
			sizeMultiplier: 5,
			edgeLabelSize: 'proportional',
			minArrowSize: '10',

			// defaultLabelSize: '20'
		}
	});
	CustomShapes.init(s);
	s.refresh();
	sigma.parsers.json('graph?id=' + id, s, function () {
		dragging(s);
		CustomShapes.init(s);
		s.refresh();
	});
	return s;
}

function dragging(s) {
	var dragListener = sigma.plugins.dragNodes(s, s.renderers[0]);
	dragListener.bind('startdrag', function (event) {
		console.log(event);
	});
	dragListener.bind('drag', function (event) {
		console.log(event);
	});
	dragListener.bind('drop', function (event) {
		console.log(event);
	});
	dragListener.bind('dragend', function (event) {
		console.log(event);
	});
}
