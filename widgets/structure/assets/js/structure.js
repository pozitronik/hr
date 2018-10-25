

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
