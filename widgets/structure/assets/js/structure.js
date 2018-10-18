
sigma.utils.pkg('sigma.canvas.nodes');
sigma.canvas.nodes.image = (function() {
	var _cache = {},
		_loading = {},
		_callbacks = {};
	// Return the renderer itself:
	var renderer = function(node, context, settings) {
		var args = arguments,
			prefix = settings('prefix') || '',
			size = node[prefix + 'size'],
			color = node.color || settings('defaultNodeColor'),
			url = node.url;
		if (_cache[url]) {
			context.save();
			// Draw the clipping disc:
			context.beginPath();
			context.arc(
				node[prefix + 'x'],
				node[prefix + 'y'],
				node[prefix + 'size'],
				0,
				Math.PI * 2,
				true
			);
			context.closePath();
			context.clip();
			// Draw the image
			context.drawImage(
				_cache[url],
				node[prefix + 'x'] - size,
				node[prefix + 'y'] - size,
				2 * size,
				2 * size
			);
			// Quit the "clipping mode":
			context.restore();
			// Draw the border:
			context.beginPath();
			context.arc(
				node[prefix + 'x'],
				node[prefix + 'y'],
				node[prefix + 'size'],
				0,
				Math.PI * 2,
				true
			);
			context.lineWidth = size / 5;
			context.strokeStyle = node.color || settings('defaultNodeColor');
			context.stroke();
		} else {
			sigma.canvas.nodes.image.cache(url);
			sigma.canvas.nodes.def.apply(
				sigma.canvas.nodes,
				args
			);
		}
	};
	// Let's add a public method to cache images, to make it possible to
	// preload images before the initial rendering:
	renderer.cache = function(url, callback) {
		if (callback)
			_callbacks[url] = callback;
		if (_loading[url])
			return;
		var img = new Image();
		img.onload = function() {
			_loading[url] = false;
			_cache[url] = img;
			if (_callbacks[url]) {
				_callbacks[url].call(this, img);
				delete _callbacks[url];
			}
		};
		_loading[url] = true;
		img.src = url;
	};
	return renderer;
})();

/*Пока костыль, работающий по логике: в цикле дложидаемся аяксовой прогрузки графа, применяем к нему хитрые преобразования*/
var IntervalId = setInterval(function wait_while_done() {
	s = sigma.instances()[0];
	if (typeof (s) !== 'undefined') {

		dragging(s);
		overlapping(s);
		clearInterval(IntervalId);
	}

}, 1000);

function overlapping(s) {
	var noverlapListener = s.configNoverlap({
		nodeMargin: 2,
		scaleNodes: 1.2,
		gridSize: 500,
		easing: 'quadraticInOut', // animation transition function
		duration: 1000  // animation duration. Long here for the purposes of this example only
	});
	noverlapListener.bind('start stop interpolate', function (e) {
		console.log(e.type);
		if (e.type === 'start') {
			console.time('noverlap');
		}
		if (e.type === 'interpolate') {
			console.timeEnd('noverlap');
		}
	});
	s.startNoverlap();
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
