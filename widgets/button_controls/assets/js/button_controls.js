'use strict';

class DashboardControl {
	constructor(selector, itemSelector) {
		var self = this;
		this.selector = selector || '.grid';
		this.itemSelector = itemSelector || '.panel-card';

		this.isotope = new Isotope(this.selector, {
			itemSelector: this.itemSelector,
			layoutMode: 'fitRows',
			getSortData: {
				count: '.count parseInt',
				vacancyCount: '.vacancy-count parseInt',
				type: '.group-type-name'
			}
		})

		$("[name='sorting']").each(function(index) {
			$(this).bind('click', function(event) {
				self.bindSorting($(this))
			})
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


	get filters() {/*return a filters selection string*/
		var r = [];
		$('input[name="filter[]"]:checked').each(function(i) {
			r.push('.' + ($(this).attr('value')));
		})
		return r.join(', ');
	}
}