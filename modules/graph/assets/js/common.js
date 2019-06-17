/**
 * DOM utility functions
 */
const _ = {
	$: function(id) {
		return document.getElementById(id);
	},

	all: function(selectors) {
		return document.querySelectorAll(selectors);
	},

	removeClass: function(selectors, cssClass) {
		const nodes = document.querySelectorAll(selectors);
		const l = nodes.length;
		for (let i = 0; i < l; i++) {
			const el = nodes[i];
			// Bootstrap compatibility
			el.className = el.className.replace(cssClass, '');
		}
	},

	addClass: function(selectors, cssClass) {
		const nodes = document.querySelectorAll(selectors);
		const l = nodes.length;
		for (i = 0; i < l; i++) {
			const el = nodes[i];
			// Bootstrap compatibility
			if (-1 === el.className.indexOf(cssClass)) {
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

	toggle: function(selectors, cssClass = 'hidden') {
		const nodes = document.querySelectorAll(selectors);
		const l = nodes.length;
		for (i = 0; i < l; i++) {
			const el = nodes[i];
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
		const url = new URL(window.location);
		return url.searchParams.get(name)
	},


};

/**
 * Проверка на пустоту
 * @param value
 * @returns {boolean|*}
 */
function isEmpty(value) {
	return null === value || value === undefined || ($.isArray(value) && 0 === value.length) || '' === value;
}

/**
 * Проверка на число
 * @param n
 * @returns {boolean}
 */
function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Проверка что элмент отмечен
 * @param element
 * @returns {*|jQuery}
 */
function isChecked(element) {
	return element.prop("checked");
}

ajax = function() {
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();

	let names,
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
			} catch (ignore) {
			}
	}

	return null;
};

getJSON = function(url, parameters) {
	return new Promise(function(resolve, reject) {
		const request = ajax();
		if (!request) {
			const error = new Error('XMLHttpRequest not supported');
			reject(error);
		}
		url += '?';
		for (let key in parameters) {
			url += encodeURIComponent(key) + '=' + encodeURIComponent(parameters[key]) + '&';
		}


		request.open('GET', url, true);
		request.onreadystatechange = function() {
			if (4 === request.readyState) {
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

};

postUrlEncoded = function(url, parameters) {
	return new Promise(function(resolve, reject) {
		const request = ajax();
		if (!request) {
			const error = new Error('XMLHttpRequest not supported');
			reject(error);
		}
		let postString = '';
		for (let key in parameters) {
			postString += encodeURIComponent(key) + '=' + encodeURIComponent(parameters[key]) + '&';
		}
		request.open('POST', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.onreadystatechange = function() {
			if (4 === request.readyState) {
				resolve(JSON.parse(request.responseText));
			}
		};
		request.onerror = function() {
			reject(new Error("Network Error"));
		};

		request.send(postString);
	});
};

/*Добавляем метод к массивам*/
Object.defineProperty(Array.prototype, 'pushOrReplace', {
	value: function(item) {
		let indexOfItem;
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
