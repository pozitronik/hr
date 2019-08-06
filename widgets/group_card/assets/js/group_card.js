function normalize_widths() {
	let containerWidth = $('.grid').width(),
		cardBaseWidth = containerWidth/5;
	$('.panel-card').each(function(index) {
		let cardWidth = $(this).width();
		let cardSizeMultiplier = Math.ceil(cardWidth / cardBaseWidth);
		$(this).addClass('grid-item--width'+cardSizeMultiplier);
	});
}