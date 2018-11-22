<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearchSet
 * @package app\models\prototypes
 *
 * @property PrototypeCompetenciesSearch[] $conditions
 */
class PrototypeCompetenciesSearchSet extends Model {
	private $conditions = [];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['conditions'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'conditions' => 'Набор условий'
		];
	}

	public function init():void {
		parent::init();
		if ([] === $this->conditions) $this->conditions[] = new PrototypeCompetenciesSearch();//По умолчанию одно условие
	}

	/**
	 * @param PrototypeCompetenciesSearch|null $condition
	 */
	public function addCondition(PrototypeCompetenciesSearch $condition = null):void {
		if (null === $condition) $condition = new PrototypeCompetenciesSearch();
		$this->conditions[] = $condition;
	}

	/**
	 * @param int $index
	 */
	public function removeCondition(int $index):void {
		ArrayHelper::remove($this->conditions, $index);
	}

	/**
	 * @return PrototypeCompetenciesSearch[]
	 */
	public function getConditions():array {
		return $this->conditions;
	}

}