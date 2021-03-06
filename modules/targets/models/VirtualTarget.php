<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use yii\db\ActiveQuery;

/**
 * Class VirtualTarget
 * @package app\modules\targets\models
 * Виртуальная цель для подсовывания в нужные места
 * @property Targets|null $relParentTarget -- вышестоящая задача целеполагания (если есть)
 * @property Targets[] $relChildTargets -- нижестоящие задачи целеполагания
 *
 */
class VirtualTarget extends Targets {
	public $relParentTarget;
	public $relChildTargets;

	/**
	 * @return Targets[]
	 */
	public function getRelChildTargets():array {
		return $this->relChildTargets;
	}

	/**
	 * @param mixed $relChildTargets
	 */
	public function setRelChildTargets($relChildTargets):void {
		$this->relChildTargets = $relChildTargets;
	}

	/**
	 * @return Targets|null
	 */
	public function getRelParentTarget():?Targets {
		return $this->relParentTarget;
	}

	/**
	 * @param mixed $parentTarget
	 */
	public function setRelParentTarget($parentTarget):void {
		$this->relParentTarget = $parentTarget;
	}
}