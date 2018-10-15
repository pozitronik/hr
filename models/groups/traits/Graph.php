<?php

namespace app\models\groups\traits;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use Exception;

/**
 * Экспериментальный трейт для групп, все функции построения графа структуры
 */
trait Graph {

	/**
	 * @param null|integer $x
	 * @param null|integer $y
	 * @return array
	 * @throws Exception
	 */
	public function asNode($x = null, $y = null):array {
		/** @var Groups $this */
		return [
			'id' => $this->id,
			'label' => $this->name,
			'x' => $x?$x:random_int(0, 100),
			'y' => $y?$y:random_int(0, 100),
			'size' => 3//todo: придумать характеристику веса группы
		];
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getGraph($isRoot = false, &$childStack = []) {
		$graph = [];

		if ($isRoot) {/*Добавляем текущуюю группу корневым узлом*/
			$graph[] = $this->asNode(0, 0);
		} else {
			$graph[] = $this->asNode();
		}
		/** @var Groups $childGroup */
		foreach ($this->relChildGroups as $childGroup) {
			if (false === ArrayHelper::getValue($childStack, $childGroup->id, false)) {
				$childStack[$childGroup->id] = true;
				$graph[] = $childGroup->getGraph(false, $childStack);
			}
		}

		return $graph;
	}
}