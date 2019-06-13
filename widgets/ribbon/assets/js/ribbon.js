$('.ribbon-tab').on('dblclick', function() {
	$('.ribbon .panel-body').slideToggle("fast",function(){
		resize_container();
	});
}).on('click', function() {
	if (!$('.ribbon .panel-body').is(":visible")) {
		$('.ribbon .panel-body').slideToggle("fast",function() {
			resize_container();
		});
	}

});