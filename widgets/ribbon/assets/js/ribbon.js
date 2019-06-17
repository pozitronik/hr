let ribbonBody = $('.ribbon .panel-body');
$('.ribbon-tab').on('dblclick', function() {
	ribbonBody.slideToggle("fast", function() {
		graphControl.resizeContainer();
	});
}).on('click', function() {
	if (!ribbonBody.is(":visible")) {
		ribbonBody.slideToggle("fast", function() {
			graphControl.resizeContainer();
		});
	}

});