const positionNone = 0, //не позиционировать ноды на сервере
	positionRound = 1;// позиционировать в круговую диаграмму

/**
 * Загружает набор нод для группы
 * @param int groupId
 * @param int positionMode
 * @return object
 */
function load_group_graph(groupId, positionMode = positionNone) {


}

/**
 * Загружает набор параметров конфигурации графа
 * @param string configName
 * @return object
 */
function load_graph_options(configName = 'default') {/*todo*/
	return {
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
			filter: function(option, path) {
				if (path.indexOf('hierarchical') !== -1) {
					return true;
				}
				return true;
			},
			showButton: true
		}
	}
}

/**
 * Загружает сохранённый набор координат нод по имени конфига
 * @param string configName
 */
function load_nodes_positions(configName = 'default') {

}

/**
 * Сохраняет набор нод в конфиг
 * @param array nodes
 * @param string configName
 */
function save_nodes_positions(nodes, configName = 'default') {

}

/**
 * Сохраняет координаты одной ноды в конфиге
 * @param object node
 * @param string configName
 */
function save_node_position(node, configName = 'default') {

}

init_tree = function(groupId) {
	var nodes,
		options = load_graph_options();


	getJSON('/groups/ajax/groups-tree?id=' + groupId).then(
		response => network = new vis.Network(_.$('tree-container'), response, options),
		error => console.log(error)
	)


	_.$('toggle-controls').onclick = function click() {
		_.toggle('#controls-block', 'min');
		_.toggle('#tree-container', 'max');
	};
	_.show('#toggle-controls');
}

