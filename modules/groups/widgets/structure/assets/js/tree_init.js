'use strict';

const positionNone = 0, //не позиционировать ноды на сервере
	positionRound = 1;// позиционировать в круговую диаграмму

class GraphControl {

	/**
	 * @param container
	 * @param groupId
	 */
	constructor(container, groupId) {
		let self = this;
		this.container = container;
		this.loadNodesPositions(groupId);
		this.network = new vis.Network(_.$('tree-container'));
		this.setOptions(self.loadGraphOptions());

		this.autofit = true;

		this.network.on('beforeDrawing', function() {
			self.resizeContainer();
		}).on('stabilized', function() {
			GraphControl.fitAnimated();
		});
		GraphControl.fitAnimated();
	}

	/**
	 * Загружает набор нод для группы
	 * @return object
	 * @param groupId
	 * @param positionMode
	 */
	loadGroupGraph(groupId, positionMode = positionNone) {
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
	 * @param groupId
	 * @param configName
	 */
	loadNodesPositions(groupId = null, configName = 'default') {//todo согласовать порядок параметров
		if (null === groupId) groupId = _.get('id');
		getJSON('/groups/ajax/groups-tree?id=' + encodeURIComponent(groupId) + '&configName=' + encodeURIComponent(configName)).then(
			response => this.network.setData(response),
			error => console.log(error)
		)
	}

	/**
	 * Сохраняет набор нод в конфиг
	 * @param configName
	 * @param groupId
	 * @param nodes
	 */
	saveNodesPositions(configName = 'default', groupId = null, nodes = null) {
		if (null === groupId) groupId = _.get('id');
		if (null === nodes) nodes = this.network.getPositions();

		postUrlEncoded('/groups/ajax/groups-tree-save-nodes-positions', 'groupId=' + encodeURIComponent(groupId) +
			'&nodes=' + encodeURIComponent(JSON.stringify(nodes)) + '&name=' + encodeURIComponent(configName)).then(
			response => console.log('nodes positions saved'),
			error => console.log(error)
		)
	}

	/**
	 * Убирает конфиг с заданным именем
	 * @param configName
	 * @param groupId
	 */
	deleteNodesPositions(configName = 'default', groupId = null) {
		if (null === groupId) groupId = _.get('id');
		postUrlEncoded('/groups/ajax/groups-tree-delete-nodes-positions', 'groupId=' + encodeURIComponent(groupId) +
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

	setOptions(graphOptions) {
		this.current_options = graphOptions;
		this.network.setOptions(this.current_options);
	}

	getOptions() {
		return this.current_options;
	}

	setPhysics(toggle = null) {
		this.options.physics.enabled = null === toggle?!this.network.physics.physicsEnabled:toggle;
	}

	setHierarchy(toggle = null) {
		this.options.layout.hierarchical.enabled = null === toggle?!this.network.layoutEngine.options.hierarchical.enabled:toggle;
	}

	setMultiselection(toggle = null) {
		this.options.interaction.multiselect = null === toggle?!this.network.selectionHandler.options.multiselect:toggle;
	}

	static fitAnimated() {
		if (true === self.autofit) {
			self.network.fit({
				animation: {
					offset: {x: 0, y: 0},
					duration: 1000,
					easingFunction: 'easeInOutQuint'
				}
			});
		}
	}

	resizeContainer() {
		$(this.container).css({'top': ($('header').height() + $('#controls-block').height()) + 'px'});
	}
}
