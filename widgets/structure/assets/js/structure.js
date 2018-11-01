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
	},

	get: function (name) {
		var url = new URL(window.location);
		return url.searchParams.get(name)
	}
};


function updatePane(graph, filter) {
	var labels = {};

	// read nodes
	graph.nodes().forEach(function (n) {
		labels[n.label] = n.id;
	})

	// node category
	var labelList = _.$('node-labels');
	Object.keys(labels).forEach(function (c) {
		var optionElt = document.createElement("option");
		optionElt.text = c;
		optionElt.value = labels[c];
		labelList.add(optionElt);
	});

	// reset button
	_.$('reset-filter').addEventListener("click", function (e) {
		_.$('node-labels').selectedIndex = 0;
		filter.undo().apply();
	});

	_.$('reset-graph').addEventListener("click", function (e) {
		s.kill();
		init_sigma(_.get('id'), 1);
	});

	_.$('toggle-control-size').onclick = function click() {
		_.toggle('#control-pane', 'min')
	};
}


function init_sigma(id, mode) {
	if ('undefined' === typeof (mode)) mode = 0;
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

	sigma.parsers.json('/ajax/groups-tree?id=' + id + '&restorePositions=' + mode, s, function () {
		bindFilter(s);//must be before bind events
		bindEvents(s);
		bindDragging(s);

		CustomShapes.init(s);
		s.refresh();
	});
	return s;
}

function bindFilter(s) {
	filter = new sigma.plugins.filter(s);

	updatePane(s.graph, filter);

	s.selectNeighborhood = function selectNeighborhood(id) {
		filter.undo().neighborsOf(id).apply()
	}

	s.applyLabelFilter = function applyLabelFilter(e) {
		var nodeId = e.target[e.target.selectedIndex].value;
		if (-1 == nodeId) {
			filter.undo().apply();
		} else {
			s.selectNeighborhood(nodeId);
		}

	}

	_.$('node-labels').addEventListener("change", s.applyLabelFilter);
}

function bindEvents(s) {
	s.bind("clickNode", function (object) {
		if (object.data.captor.shiftKey) {
			nodeId = object.data.node.id;
			s.selectNeighborhood(nodeId);
			_.$('node-labels').value = nodeId;

		}
	});

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
	dragListener.bind('dragend', function (event) {
		save_node_position(event.data.node.id, event.data.node.x, event.data.node.y);
	});
}

function save_node_position(node_id, x, y) {
	var xhr = sigma.utils.xhr();

	if (!xhr) throw 'XMLHttpRequest not supported.';

	var request_body = 'groupId=' + encodeURIComponent(_.get('id')) +
		'&nodeId=' + encodeURIComponent(node_id)
		+ '&x=' + encodeURIComponent(x)
		+ '&y=' + encodeURIComponent(y);
	xhr.open('POST', '/ajax/groups-tree-save-node-position', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');


	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			var response = JSON.parse(xhr.responseText);
			console.log(response);
		}
	};
	xhr.send(request_body);

	console.log(node_id, x, y);

}
