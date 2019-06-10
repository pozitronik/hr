<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use MongoDB\Driver\Exception\ConnectionException;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;

/**
 * Прототип конфигурации позиции нод (карты)
 * Class NodesPositionsConfig
 * @package app\models\prototypes
 *
 * @property string $name
 * @property integer $groupId
 * @property array $nodes
 */
class NodesPositionsConfig extends Model {
	public $name;
	public $groupId;
	public $nodes = [];

	/**
	 * Загружает массив позиций нод
	 * @param array $nodes
	 */
	public function loadNodes(array $nodes):void {
		foreach ($nodes as $nodeId => $node) {
			$nodeData = new PrototypeNodeData([//used fot validation only
				'nodeId' => $nodeId
			]);

			if ($nodeData->load($node, '')) {
				$this->nodes[$nodeId] = [
					'x' => $nodeData->x,
					'y' => $nodeData->y
				];
			} else {
				$this->addErrors($nodeData->errors);
			}
		}
	}

	/**
	 * Загружаем конфигурацию из массива в модель
	 * @param array $configArray
	 * @return NodesPositionsConfig
	 * @throws Throwable
	 */
	public function fromArray(array $configArray):self {
		$this->name = ArrayHelper::getValue($configArray, 'name', new ConnectionException('Undefined config name'));
		$this->groupId = ArrayHelper::getValue($configArray, 'groupId', new ConnectionException('Undefined group ID'));
		$this->loadNodes(ArrayHelper::getValue($configArray, 'nodes', []));
		return $this;
	}

	/**
	 * Выгружаем в виде массива для хранения в опциях
	 * @return array
	 */
	public function asArray():array {
		return [
			'name' => $this->name,
			'groupId' => $this->groupId,
			'nodes' => $this->nodes
		];
	}
}