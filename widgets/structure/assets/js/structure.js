function init_sigma(id) {
	s = new sigma({
		renderer: {
			container: document.getElementById('sigma-container'),
			type: 'canvas'
		},
		settings: {
			zoomingRatio: 1.3,
			enableEdgeHovering: true,
			edgeHoverPrecision: 1,//Точность определения курсора над мышью, слишком большое значение тормозит
			edgeHoverSizeRatio: 3,//увеличение графа при наведении
			// sizeMultiplier: 4,
			edgeLabelSize: 'proportional',
			minArrowSize: '10',
			labelThreshold: 30,
			nodesPowRatio: 1,
			edgesPowRatio: 1,
			maxNodeSize: 40
			// defaultLabelSize: '20'
		}
	});
	CustomShapes.init(s);
	s.refresh();
	sigma.parsers.json('graph?id=' + id, s, function () {
		bindOpener(s);
		dragging(s);
		CustomShapes.init(s);
		s.refresh();
	});
	return s;
}

function bindOpener(s) {
	// s.bind("doubleClickNode", function (object) {
	// 	window.open('update?id=' + object.data.node.id);
	// });
}

function dragging(s) {
	var dragListener = sigma.plugins.dragNodes(s, s.renderers[0]);
	// dragListener.bind('startdrag', function (event) {
	// 	console.log(event);
	// });
	// dragListener.bind('drag', function (event) {
	// 	console.log(event);
	// });
	// dragListener.bind('drop', function (event) {
	// 	console.log(event);
	// });
	// dragListener.bind('dragend', function (event) {
	// 	console.log(event);
	// });
}
