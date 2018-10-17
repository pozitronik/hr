<?php
declare(strict_types = 1);

namespace app\models\groups\traits;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use Exception;
use Throwable;

/**
 * Экспериментальный трейт для групп, все функции построения графа структуры
 */
trait Graph {

	/**
	 * @param null|integer $position
	 * @param null|integer $level
	 * @return array
	 * @throws Exception
	 */
	public function asNode($position = 0, $level = 0):array {
		/** @var Groups $this */
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
		return [
			'id' => (string)$this->id,
			'label' => $this->name,
			'x' => $position,
			'y' => $level,
			'size' => (string)3,//count($this->relUsers),//todo: придумать характеристику веса группы,
			'color' => "rgb({$red},{$green},{$blue})"
		];
	}

	/**
	 * @param self $to
	 * @return array
	 */
	private function Edge($to):array {
		return [
			'id' => "{$this->id}x{$to->id}",
			'source' => (string)$this->id,
			'target' => (string)$to->id,
			'type' => 'curvedArrow',
			'label' => $to->relGroupTypes->name,
			'size' => '30'
		];
	}

	/**
	 * @param array $graphStack
	 * @param array $edgesStack
	 * @param array $childStack
	 * @param int $level
	 * @param int $position
	 * @throws Throwable
	 */
	public function getGraph(&$graphStack = [], &$edgesStack = [], array &$childStack = [], &$level = 0, $position = 0):void {
		/** @var Groups $this */
		$childStack[$this->id] = true;
		$graphStack[] = $this->asNode($position, $level);
		/** @var Groups $childGroup */
		/** @noinspection ForeachSourceInspection */
		foreach ($this->relChildGroups as $childGroup) {
			$edgesStack[] = $this->Edge($childGroup);
			if (false === ArrayHelper::getValue($childStack, $childGroup->id, false)) {
				$childStack[$childGroup->id] = true;
				$level++;
				$childGroup->getGraph($graphStack, $edgesStack, $childStack, $level, $position);
				$level--;
				$position++;
			}
		}

	}
}