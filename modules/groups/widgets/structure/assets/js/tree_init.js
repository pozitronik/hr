const positionNone = 0, //не позиционировать ноды на сервере
	positionRound = 1;// позиционировать в круговую диаграмму

var network = new vis.Network(_.$('tree-container'));

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
				enabled: false,
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
			enabled: false,
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
 * @param int groupId
 * @param string configName
 */
function load_nodes_positions(groupId = null, configName = 'default') {//todo согласовать порядок параметров
	if (null === groupId) groupId = _.get('id');
	getJSON('/groups/ajax/groups-tree?id=' + encodeURIComponent(groupId) + '&configName=' + encodeURIComponent(configName)).then(
		response => network.setData(response),
		error => console.log(error)
	)
}

/**
 * Сохраняет набор нод в конфиг
 * @param string configName
 * @param int|null groupId
 * @param array|null nodes
 */
function save_nodes_positions(configName = 'default', groupId = null, nodes = null) {
	if (null === groupId) groupId = _.get('id');
	if (null === nodes) nodes = network.getPositions();
	var request_body = 'groupId=' + encodeURIComponent(groupId) +
		'&nodes=' + encodeURIComponent(JSON.stringify(nodes)) + '&name=' + encodeURIComponent(configName);
	postUrlEncoded('/groups/ajax/groups-tree-save-nodes-positions', request_body).then(
		response => console.log('nodes positions saved'),
		error => console.log(error)
	)
}

/**
 * Убирает конфиг с заданным именем
 * @param string configName
 * @param int|null groupId
 */
function delete_nodes_positions(configName = 'default', groupId = null) {
	if (null === groupId) groupId = _.get('id');
	var request_body = 'groupId=' + encodeURIComponent(groupId) +
		'&name=' + encodeURIComponent(configName);
	postUrlEncoded('/groups/ajax/groups-tree-delete-nodes-positions', request_body).then(
		response => console.log('nodes positions saved'),
		error => console.log(error)
	)
}

/**
 * Сохраняет координаты одной ноды в конфиге
 * @param object node
 * @param string configName
 */
function save_node_position(node, configName = 'default') {

}

function fitAnimated() {
	var options = {
		offset: {x: 0, y: 0},
		duration: 1000,
		easingFunction: 'easeInOutQuint'
	};
	network.fit({animation: options});
}

/**
 * Переключаем физический движок
 * @param bool|null toggle
 */
function togglePhysics(toggle = null) {
	network.setOptions({
		physics: {
			enabled: null === toggle ? !network.physics.physicsEnabled : toggle
		},
	});
}

init_tree = function(groupId) {
	load_nodes_positions(groupId)

	network.setOptions(load_graph_options());

	// network.addEventListener("dragEnd", function() {
	// 	save_nodes_positions();
	// });

	// _.$('toggle-controls').onclick = function click() {
	// 	_.toggle('#controls-block', 'min');
	// 	_.toggle('#tree-container', 'max');
	// };
	// _.show('#toggle-controls');
}


