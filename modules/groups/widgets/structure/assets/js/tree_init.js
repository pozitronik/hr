

init_tree = function (id) {
	var data = {};
	json('/groups/ajax/groups-tree?id=' + id, data, function (tree_data) {
		var network = new vis.Network(_.$('tree-container'), tree_data, {
			locale: 'ru',
			layout: {
				randomSeed: undefined,
				improvedLayout: true,
				hierarchical: {
					direction: "UD",
					enabled: true,
					levelSeparation: 200,
					nodeSpacing: 200,
					treeSpacing: 200,
					blockShifting: true,
					edgeMinimization: true,
					parentCentralization: true,
					sortMethod: 'directed'   // hubsize, directed

				}
			},
			interaction: {dragNodes: true},
			physics: {
				enabled: false
			},
			configure: {
				container: _.$('controls-block'),
				enabled: true,
				filter: function (option, path) {
					if (path.indexOf('hierarchical') !== -1) {
						return true;
					}
					return true;
				},
				showButton: true
			}
		});
	});

	_.$('toggle-controls').onclick = function click() {
		_.toggle('#controls-block', 'min');
		_.toggle('#tree-container', 'max');
	};
}

