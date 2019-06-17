'use strict';

const positionNone = 0, //не позиционировать ноды на сервере
	positionRound = 1;// позиционировать в круговую диаграмму
/*адреса для ajax-запросов*/
const URL_LOAD_GRAPH = '/graph/groups/graph',//загрузка структуры
	URL_LOAD_OPTIONS = '',//загрузка параметров визуализации
	URL_SAVE_OPTIONS = '',//сохранение ----
	URL_DELETE_OPTIONS = '',//удаление ----
	URL_LOAD_POSITIONS = '',//загрузка позиций
	URL_SAVE_POSITIONS = '',//сохранение ----
	URL_DELETE_POSITIONS = '';//удаление ----

class GraphControl {

	/**
	 * @param container
	 * @param groupId
	 */
	constructor(container, groupId) {
		let self = this;
		this.groupId = groupId || _.get('id');
		this.container = container;
		this.loadGraph();

		// this.loadNodesPositions(groupId);
		this.network = new vis.Network(_.$('tree-container'));
		this.options = self.loadGraphOptions();

		this.autofit = true;

		this.network.on('beforeDrawing', function() {
			self.resizeContainer();
		}).on('stabilized', function() {
			self.fitAnimated();
		});
		self.fitAnimated();
	}

	loadGraph() {
		getJSON(URL_LOAD_GRAPH + '?id=' + encodeURIComponent(this.groupId)).then(
			response => this.network.setData(response),
			error => console.log(error)
		)
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
			}
		}
	}

	/**
	 * Загружает сохранённый набор координат нод по имени конфига
	 * @param configName
	 */
	loadNodesPositions(configName = 'default') {//todo согласовать порядок параметров
		getJSON('/groups/ajax/groups-tree?id=' + encodeURIComponent(this.groupId) + '&configName=' + encodeURIComponent(configName)).then(
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

		postUrlEncoded('/groups/ajax/groups-tree-save-nodes-positions', 'groupId=' + encodeURIComponent(this.groupId) +
			'&nodes=' + encodeURIComponent(JSON.stringify(nodes)) + '&name=' + encodeURIComponent(configName)).then(
			response => console.log('nodes positions saved'),
			error => console.log(error)
		)
	}

	/**
	 * Убирает конфиг с заданным именем
	 * @param configName
	 */
	deleteNodesPositions(configName = 'default') {
		postUrlEncoded('/groups/ajax/groups-tree-delete-nodes-positions', 'groupId=' + encodeURIComponent(this.groupId) +
			'&name=' + encodeURIComponent(configName)).then(
			response => console.log('nodes positions saved'),
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

	fitAnimated() {
		this.network.fit({
			animation: {
				offset: {x: 0, y: 0},
				duration: 1000,
				easingFunction: 'easeInOutQuint'
			}
		});
	}

	resizeContainer() {
		$(this.container).css({'top': ($('header').height() + $('#controls-block').height()) + 'px'});
	}
}
