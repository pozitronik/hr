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
	},


};

xhr = function () {
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();

	var names,
		i;

	if (window.ActiveXObject) {
		names = [
			'Msxml2.XMLHTTP.6.0',
			'Msxml2.XMLHTTP.3.0',
			'Msxml2.XMLHTTP',
			'Microsoft.XMLHTTP'
		];

		for (i in names)
			try {
				return new ActiveXObject(names[i]);
			} catch (e) {
			}
	}

	return null;
};

json = function (from_url, data, callback) {
	var graph,
		request = xhr();

	if (!request)
		throw 'XMLHttpRequest not supported, cannot load the file.';

	request.open('GET', from_url, true);
	request.onreadystatechange = function () {
		if (request.readyState === 4) {
			graph = JSON.parse(request.responseText);

			// Update the instance's graph:
			//todo

			// Call the callback if specified:
			if (callback)
				callback(data || graph);
		}
	};
	request.send();
};

init_tree = function (id) {
	var data = {},
		options = {},
		container = _.$('tree-container');
	json('/groups/ajax/groups-tree?id=' + id, data);
	var network = new vis.Network(container, data, options);

}

