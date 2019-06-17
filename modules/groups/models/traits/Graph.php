<?php
declare(strict_types = 1);

namespace app\modules\groups\models\traits;

use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use app\modules\groups\models\Groups;
use app\models\relations\RelGroupsGroups;
use Exception;
use Throwable;

/**
 * Экспериментальный трейт для групп, все функции построения графа структуры
 */
trait Graph {

	/**
	 * @param null|integer $x
	 * @param null|integer $y
	 * @return array
	 * @throws Exception
	 * @throws Throwable
	 */
	public function asNode(?int $x = 0, ?int $y = 0):array {
		/** @var Groups $this */
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
		$size = 50 / ($y + 1);
		return [
			'id' => "group{$this->id}",
			'label' => (string)$this->name,
			'x' => $x,
			'y' => $y,
			'size' => (string)$size,//придумать характеристику веса группы,
			'color' => ArrayHelper::getValue($this->relGroupTypes, 'color', "rgb({$red},{$green},{$blue})"),
			'type' => 'circle',
			'shape' => 'image',
			'image' => $this->logo,
			'widthConstraint' => true
		];
	}

	/**
	 * @param Groups $to
	 * @return array
	 */
	private function Edge(Groups $to):array {
		return [
			'id' => "Group{$this->id}xGroup{$to->id}",
			'from' => "group{$this->id}",
			'to' => "group{$to->id}",
			'label' => $to->leader->username,
			'color' => RelGroupsGroups::getRelationColor($this->id, $to->id)
		];
	}

	/**
	 * @param array $graphStack
	 * @param array $edgesStack
	 * @param array $childStack
	 * @param array $usersStack
	 * @param int $x
	 * @param int $y
	 * @throws Throwable
	 */
	public function getGraph(array &$graphStack = [], array &$edgesStack = [], array &$childStack = [], array &$usersStack = [], int &$x = 0, int &$y = 0):void {
		/** @var Groups $this */
		$childStack[$this->id] = true;
		$graphStack[] = $this->asNode($x, $y);
		/** @var Groups $childGroup */
		$y++;
		/** @noinspection ForeachSourceInspection */
		foreach ($this->relChildGroups as $childGroup) {
			$edgesStack[] = $this->Edge($childGroup);

			if (false === ArrayHelper::getValue($childStack, $childGroup->id, false)) {
				$childStack[$childGroup->id] = true;
				$childGroup->getGraph($graphStack, $edgesStack, $childStack, $usersStack, $x, $y);
			}
		}

		/** @var Users $user */
		foreach ($this->relUsers as $user) {

			if (!in_array($user->id, $usersStack)) {

				$graphStack[] = $user->asNode($x, $y);
				/** @var Groups $this */
				$edgesStack[] = $user->Edge($this);
				$usersStack[] = $user->id;
			}

		}

		$x++;
		$y--;
	}

	/**
	 * Строим матрицу распределения узлов графа структуры
	 * @param array $graphMap
	 * @param int $level
	 */
	public function getGraphMap(array &$graphMap = [0 => 0], int &$level = 0):void {
		/** @var Groups $this */
		if (!isset($graphMap[$level + 1])) $graphMap[$level + 1] = 0;
		$graphMap[$level + 1] += count($this->relChildGroups);

		/** @var Groups $childGroup */

		/** @noinspection ForeachSourceInspection */
		foreach ($this->relChildGroups as $childGroup) {
			$level++;
			$childGroup->getGraphMap($graphMap, $level);
			$level--;
		}
	}

	/**
	 * Пересчитываем координаты графа в круговые
	 * @param array $nodes
	 */
	public function roundGraph(array &$nodes):void {
		$levelMap = [];
		$newNodes = [];
		foreach ($nodes as $node) {
			$levelMap[$node['y']][] = $node;
		}
		foreach ($levelMap as $level => $items) {
			$c_items = count($items) / 2;//Я не знаю, зачем делить на два, я не академик
			$degree = 360 / $c_items;//Угловое смещение точки

			$radius = (0 === $level)?$level:($level + 1);
			$radius *= 360;
			$angle = 0;//Стартовый угол, 0 - 360
			/** @var array $items */
			foreach ($items as $item) {
				$item['x'] = ($radius * cos($angle * M_PI / 360));
				$item['y'] = ($radius * sin($angle * M_PI / 360));

				$angle += $degree;
				$newNodes[] = $item;
			}
		}
		$nodes = $newNodes;
	}

	/**
	 * Заменяет высчитанные позиции нод заданными
	 * @param array $nodes
	 * @param array $positions
	 */
	public
	function applyNodesPositions(array &$nodes, array $positions = []):void {
		foreach ($positions as $nodeId => $position) {
			if (false !== ($key = array_search($nodeId, array_column($nodes, 'id')))) {
				/** @var integer $key */
				$nodes[$key]['x'] = $position['x'];
				$nodes[$key]['y'] = $position['y'];
			}

		}
	}
}