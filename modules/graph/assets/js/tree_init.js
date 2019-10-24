'use strict';

const positionNone = 0, //не позиционировать ноды на сервере
	positionRound = 1;// позиционировать в круговую диаграмму
/*адреса для ajax-запросов*/
const URL_LOAD_GRAPH = '/graph/groups/graph',//загрузка структуры
	URL_LOAD_GRAPH_USER = '/graph/groups/user-graph',//загрузка структуры
	URL_LOAD_OPTIONS = '',//загрузка параметров визуализации
	URL_SAVE_OPTIONS = '',//сохранение ----
	URL_DELETE_OPTIONS = '',//удаление ----
	URL_LOAD_POSITIONS = '/graph/groups/load-positions',//загрузка позиций
	URL_SAVE_POSITIONS = '/graph/groups/save-positions',//сохранение ----
	URL_DELETE_POSITIONS = '/graph/groups/delete-positions',//удаление ----
	URL_GROUPS_PROFILE = '/groups/groups/profile';//профиль группы

class GraphControl {

	/**
	 * @param container
	 * @param groupId
	 * @param userId
	 * @param downDepth
	 * @param upDepth
	 * @param downDepth
	 * @param upDepth
	 */
	constructor(container, groupId, userId, downDepth, upDepth) {
		let self = this;
		this.groupId = groupId || _.get('id');
		this.userId = userId || _.get('user_id');
		this.container = container;
		this._downDepth = downDepth || 0;
		this._upDepth = upDepth || 0;
		// this.loadGraph();

		// this.loadNodesPositions(groupId);
		this.network = new vis.Network(this.container);
		this.options = self.loadGraphOptions();

		this.autofit = false;
		this.nodeSet = new vis.DataSet([]);
		this.edgeSet = new vis.DataSet([]);
		this.network.setData({
			nodes: this.nodeSet,
			edges: this.edgeSet
		});

		this.loadData();

		this.network.on("doubleClick", function(params) {
			if (0 === params.nodes.length) return;
			let nodeId = params.nodes[0];
			let id = nodeId.substring(6);
			window.open(URL_GROUPS_PROFILE + '?id=' + id, '_blank');
		});

		this.network.on('beforeDrawing', function() {
			self.resizeContainer();
		}).on('stabilized', function() {
			self.fitAnimated(false);
		});
		self.fitAnimated();
	}

	loadData() {
		let url;
		let id;
		if (-1 === this.userId) {
			url = URL_LOAD_GRAPH;
			id = this.groupId;
		} else {
			url = URL_LOAD_GRAPH_USER;
			id = this.userId;
		}

		getJSON(url, {
			id: id,
			up: this._upDepth,
			down: this._downDepth
		}).then(
			response => {
				this.nodes = response.nodes;
				this.edges = response.edges;
			},
			error => console.log(error)
		)
	}

	set nodes(nodes) {
		this.nodeSet.clear();
		this.nodeSet.add(nodes);
	}

	set edges(edges) {
		this.edgeSet.clear();
		this.edgeSet.add(edges);
	}


	/**
	 * Загружает набор нод для группы
	 * @return object
	 * @param positionMode
	 */
	loadGroupGraph(positionMode = positionNone) {
	}

	/**
	 * Загружает набор параметров конфигурации графа
	 * @return object
	 * @param configName
	 */
	loadGraphOptions(configName = 'default') {/*todo*/
		return {
			locale: 'ru',
			edges: {
				arrows: {
					to: {enabled: true, scaleFactor: 1, type: 'arrow'},
				},
				width: 3
			},
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
				enabled: true
			}
		}
	}

	/**
	 * Загружает сохранённый набор координат нод по имени конфига
	 * @param configName
	 */
	loadNodesPositions(configName = 'default') {
		getJSON(URL_LOAD_POSITIONS, {
			id: this.groupId,
			configName: configName
		}).then(
			response => this.network.setData(response),
			error => console.log(error)
		)
	}

	/**
	 * Сохраняет набор нод в конфиг
	 * @param configName
	 * @param nodes
	 */
	saveNodesPositions(configName = 'default', nodes = null) {
		if (null === nodes) nodes = this.network.getPositions();

		postUrlEncoded(URL_SAVE_POSITIONS, {
			id: this.groupId,
			configName: configName,
			nodes: JSON.stringify(nodes)
		}).then(
			response => console.log('nodes positions saved'),
			error => console.log(error)
		)
	}

	/**
	 * Убирает конфиг с заданным именем
	 * @param configName
	 */
	deleteNodesPositions(configName = 'default') {
		getJSON(URL_DELETE_POSITIONS, {
			id: this.groupId,
			configName: configName
		}).then(
			response => console.log('nodes positions deleted'),
			error => console.log(error)
		)
	}

	/**
	 * Сохраняет координаты одной ноды в конфиге
	 * @param node
	 * @param configName
	 */
	saveNodePosition(node, configName = 'default') {

	}

	set options(graphOptions) {
		this.current_options = graphOptions;
		this.network.setOptions(this.current_options);
	}

	get options() {
		return this.current_options;
	}

	set physics(physics) {
		this.current_options.physics.enabled = physics;
		this.options = this.current_options;
	}

	set hierarchy(hierarchy) {
		this.current_options.layout.hierarchical.enabled = hierarchy;
		this.options = this.current_options;
	}

	set multiselection(multiselection) {
		this.current_options.interaction.multiselect = multiselection;
		this.options = this.current_options;
	}

	set upDepth(upDepth) {
		this._upDepth = upDepth;
		this.loadData();
	}

	get upDepth() {
		return this._upDepth;
	}

	set downDepth(downDepth) {
		this._downDepth = downDepth;
		this.loadData();
	}

	get downDepth() {
		return this._downDepth;
	}


	fitAnimated(always = true) {
		if (always || this.autofit) {
			this.network.fit({
				animation: {
					offset: {x: 0, y: 0},
					duration: 1000,
					easingFunction: 'easeInOutQuint'
				}
			});
		}
	}

	setLevels(up = 0, down = 0) {

	}

	resizeContainer() {
		$(this.container).css({'top': ($('header').height() + $('#controls-block').height()) + 'px'});
	}
}
