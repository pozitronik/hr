'use strict';

class DashboardControl {
	constructor(selector, itemSelector, onArrangeComplete) {
		let self = this;
		this.selector = selector || '.grid';
		this.itemSelector = itemSelector || '.panel-card';
		this.onArrangeComplete = onArrangeComplete || null;

		this.isotope = new Isotope(this.selector, {
			itemSelector: this.itemSelector,
			layoutMode: 'fitRows',
			getSortData: {
				count: '.count parseInt',
				vacancyCount: '.vacancy-count parseInt',
				type: '.group-type-name'
			}
		});
		if (null !== this.onArrangeComplete)
			this.isotope.on('arrangeComplete', this.onArrangeComplete);

		$("[name='sorting']").bind('click', function(event) {
			self.bindSorting($(this))
		});

		$("[name='filter[]']").bind('click', function(event) {
			self.refresh()
		});

	}

	bindSorting(element) {
		let sortingAttribute = element.data('sorting');
		if (element.is('.ascending')) {
			this.isotope.arrange({sortBy: sortingAttribute, sortAscending: false});
			element.removeClass('ascending');
		} else {
			element.addClass('ascending');
			this.isotope.arrange({sortBy: sortingAttribute, sortAscending: true});
		}
	}

	refresh() {
		this.isotope.arrange({filter: this.filters});
	}


	get filters() {/*return a filters selection string*/
		let r = [];
		$('input[name="filter[]"]:checked').each(function(i) {
			let filterAttribute = $(this).data('filter');
			r.push("[data-filter='" + filterAttribute + "']");
		});
		if (0 === r.length) return 'empty-selection';
		return r.join(', ');
	}

	get filtersValues() {/*return a filters values as array*/
		let r = [];
		$('input[name="filter[]"]:checked').each(function(i) {
			r.push($(this).data('filter'));
		});
		return r;
	}
}