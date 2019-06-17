<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\ArrayableTrait;
use yii\base\Model;

/**
 * Класс построения графа групп
 * Class GroupGraph
 * @package app\modules\graph\models
 *
 * @property $upDepth -- глубина построения дерева вверх. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property $downDepth -- глубина построения дерева вниз. 0 - только текущий уровень, отрицательное значение - нет ограничения
 * @property GroupNode[] $nodes
 * @property GroupEdge[] $edges
 */
class GroupGraph extends Model {//todo GraphInterface
	use ArrayableTrait;

	private $upDepth = -1;
	private $downDepth = -1;
	public $nodes = [];
	public $edges = [];

	/**
	 * {@inheritDoc}
	 */
	public function __construct(Groups $group, $config = []) {
		parent::__construct($config);
		$this->buildGraph($group);
	}

	/**
	 * Строит двунаправленный граф для указанной группы
	 * @param Groups $group
	 */
	public function buildGraph(Groups $group):void {
		$processedStack[$group->id] = true;
		$this->nodes[] = new GroupNode($group);
		$this->buildGraphUp($group, $processedStack);
		$this->buildGraphDown($group, $processedStack);
	}

	/**
	 * Строит граф вниз от указанной группы
	 * @param Groups $group
	 * @param array $processedStack -- массив обработанных групп для предотвращения зацикливания
	 * @throws Throwable
	 */
	public function buildGraphDown(Groups $group, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->downDepth < 0 || $currentDepth < $this->downDepth) {
			$currentDepth++;
			$processedStack[$group->id] = true;
			/** @var Groups $childGroup */
			foreach ((array)$group->relChildGroups as $childGroup) {

				$this->nodes[] = new GroupNode($childGroup);
				$this->edges[] = new GroupEdge($group, $childGroup);

				if (false === ArrayHelper::getValue($processedStack, $childGroup->id, false)) {
//					$processedStack[$childGroup->id] = true;
					$this->buildGraphDown($childGroup, $processedStack, $currentDepth);
				}
			}
		}
	}

	/**
	 * Строит граф вверх от указанной группы
	 * @param Groups $group
	 * @param array $processedStack -- массив обработанных групп для предотвращения зацикливания
	 * @throws Throwable
	 */
	public function buildGraphUp(Groups $group, array &$processedStack = [], int &$currentDepth = 0):void {
		if ($this->upDepth < 0 || $currentDepth < $this->upDepth) {
			$processedStack[$group->id] = true;
			$currentDepth++;
			/** @var Groups $parentGroup */
			foreach ((array)$group->relParentGroups as $parentGroup) {

				$this->nodes[] = new GroupNode($parentGroup);
				$this->edges[] = new GroupEdge($parentGroup, $group);

				if (false === ArrayHelper::getValue($processedStack, $parentGroup->id, false)) {
//					$processedStack[$parentGroup->id] = true;
					$this->buildGraphDown($parentGroup, $processedStack, $currentDepth);
				}
			}
		}
	}

	/**
	 * @param mixed $upDepth
	 */
	public function setUpDepth($upDepth):void {
		$this->upDepth = $upDepth;
	}

	/**
	 * @param mixed $downDepth
	 */
	public function setDownDepth($downDepth):void {
		$this->downDepth = $downDepth;
	}
}