/**
 * DOM utility functions
 */
var _ = {
	$: function (id) {
		return document.getElementById(id);
	},

	all: function (selectors) {
		return document.querySelectorAll(selectors);
	},

	removeClass: function (selectors, cssClass) {
		var nodes = document.querySelectorAll(selectors);
		var l = nodes.length;
		for (i = 0; i < l; i++) {
			var el = nodes[i];
			// Bootstrap compatibility
			el.className = el.className.replace(cssClass, '');
		}
	},

	addClass: function (selectors, cssClass) {
		var nodes = document.querySelectorAll(selectors);
		var l = nodes.length;
		for (i = 0; i < l; i++) {
			var el = nodes[i];
			// Bootstrap compatibility
			if (-1 == el.className.indexOf(cssClass)) {
				el.className += ' ' + cssClass;
			}
		}
	},

	show: function (selectors) {
		this.removeClass(selectors, 'hidden');
	},

	hide: function (selectors) {
		this.addClass(selectors, 'hidden');
	},

	toggle: function (selectors, cssClass) {
		var cssClass = cssClass || "hidden";
		var nodes = document.querySelectorAll(selectors);
		var l = nodes.length;
		for (i = 0; i < l; i++) {
			var el = nodes[i];
			//el.style.display = (el.style.display != 'none' ? 'none' : '' );
			// Bootstrap compatibility
			if (-1 !== el.className.indexOf(cssClass)) {
				el.className = el.className.replace(cssClass, '');
			} else {
				el.className += ' ' + cssClass;
			}
		}
	}
};


function updatePane(graph, filter) {
	var categories = {},
		ids = {};

	// read nodes
	graph.nodes().forEach(function (n) {
		categories[n.label] = true;
		ids[n.id] = true;
	})

	// node category
	var nodecategoryElt = _.$('node-category');
	var nodelabelElt = _.$('node-label');
	Object.keys(ids).forEach(function (c) {
		var optionElt = document.createElement("option");
		optionElt.text = c;
		nodelabelElt.add(optionElt);
	});

	// reset button
	_.$('reset-btn').addEventListener("click", function (e) {
		_.$('node-category').selectedIndex = 0;
		filter.undo().apply();
	});

}

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
			// nodesPowRatio: 1,
			// edgesPowRatio: 1,
			maxNodeSize: 40
			// defaultLabelSize: '20'
		}
	});
	CustomShapes.init(s);
	s.refresh();
	sigma.parsers.json('graph?id=' + id, s, function () {
		bindEvents(s);
		bindDragging(s);
		bindFilter(s);
		CustomShapes.init(s);
		s.refresh();
	});
	return s;
}

function bindFilter(s) {
	filter = new sigma.plugins.filter(s);

	updatePane(s.graph, filter);

	function selectNeighborhood(e) {
		var c = e.target[e.target.selectedIndex].value;
		filter
			.undo('node-label')
			.nodesBy(function (n) {
				return !c.length || n.id === c;
			}, 'node-label')
			.apply();
	}

	function applyCategoryFilter(e) {
		var c = e.target[e.target.selectedIndex].value;
		filter
			.undo('node-category')
			.nodesBy(function (n) {
				return !c.length || n.label === c;
			}, 'node-category')
			.apply();
	}

	_.$('node-label').addEventListener("change", selectNeighborhood);
	_.$('node-category').addEventListener("change", applyCategoryFilter);
}

function bindEvents(s) {
	s.bind("doubleClickNode", function (object) {
		window.open('update?id=' + object.data.node.id);
	});
	s.bind("overNode", function (object) {
		document.getElementsByTagName("body")[0].style.cursor = 'pointer'
	});
	s.bind("outNode", function (object) {
		document.getElementsByTagName("body")[0].style.cursor = 'default'
	});
}

function bindDragging(s) {
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
