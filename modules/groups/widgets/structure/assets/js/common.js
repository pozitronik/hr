/**
 * DOM utility functions
 */
var _ = {
	$: function(id) {
		return document.getElementById(id);
	},

	all: function(selectors) {
		return document.querySelectorAll(selectors);
	},

	removeClass: function(selectors, cssClass) {
		var nodes = document.querySelectorAll(selectors);
		var l = nodes.length;
		for (i = 0; i < l; i++) {
			var el = nodes[i];
			// Bootstrap compatibility
			el.className = el.className.replace(cssClass, '');
		}
	},

	addClass: function(selectors, cssClass) {
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

	show: function(selectors) {
		this.removeClass(selectors, 'hidden');
	},

	hide: function(selectors) {
		this.addClass(selectors, 'hidden');
	},

	toggle: function(selectors, cssClass) {
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

	get: function(name) {
		var url = new URL(window.location);
		return url.searchParams.get(name)
	},


};

/**
 * Проверка на пустоту
 * @param value
 * @returns {boolean|*}
 */
function isEmpty (value) {
	return value === null || value === undefined || ($.isArray(value) && value.length === 0) || value === '';
}
/**
 * Проверка на число
 * @param n
 * @returns {boolean}
 */
function isNumeric (n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
/**
 * Проверка что элмент отмечен
 * @param element
 * @returns {*|jQuery}
 */
function isChecked (element) {
	return element.prop("checked");
}

ajax = function() {
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

getJSON = function(url) {
	return new Promise(function(resolve, reject) {
		var request = ajax();
		if (!request) {
			var error = new Error('XMLHttpRequest not supported');
			reject(error);
		}
		request.open('GET', url, true);
		request.onreadystatechange = function() {
			if (request.readyState === 4) {
				resolve(JSON.parse(request.responseText));
			}
		};
		request.onerror = function() {
			reject(new Error("Network Error"));
		};

		request.send();
	});
};

postJSON = function(url, json) {

}

postUrlEncoded = function(url, postString) {
	return new Promise(function(resolve, reject) {
		var request = ajax();
		if (!request) {
			var error = new Error('XMLHttpRequest not supported');
			reject(error);
		}
		request.open('POST', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.onreadystatechange = function() {
			if (request.readyState === 4) {
				resolve(JSON.parse(request.responseText));
			}
		};
		request.onerror = function() {
			reject(new Error("Network Error"));
		};

		request.send(postString);
	});
}

/*Добавляем метод к массивам*/
Object.defineProperty(Array.prototype, 'pushOrReplace', {
	value: function(item) {
		var indexOfItem
		if (-1 === (indexOfItem = this.indexOf(item))) {
			this.push(item);
		} else {
			this[indexOfItem] = item;
		}
	},
	enumerable: false,
	configurable: false,
	writable: false
});
