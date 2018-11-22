<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearchSet
 * @package app\models\prototypes
 *
 * @property PrototypeCompetenciesSearch[] $conditions
 *
 *
 *
 * @property boolean[] $logic;
 * @property Competencies[]|null[] $competency;
 * @property CompetencyField[]|null $field;
 * @property PrototypeCompetencySearchCondition[]|null $condition;
 * @property string[]|null[] $value
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

	/******************************************/

	/**
	 * @return boolean[]
	 */
	public function getLogic():array {
		return ArrayHelper::getColumn($this->conditions, 'logic');
	}

	/**
	 * @param boolean[] $logic
	 */
	public function setLogic(array $logic):void {
	}

	/**
	 * @return Competencies[]|null[]
	 */
	public function getCompetency():?array {
		return ArrayHelper::getColumn($this->conditions, 'competency');
	}

	/**
	 * @param Competencies[]|null[] $competency
	 */
	public function setCompetency(?array $competency):void {
	}

	/**
	 * @return CompetencyField[]|null
	 */
	public function getField():?array {
		return ArrayHelper::getColumn($this->conditions, 'field');
	}

	/**
	 * @param CompetencyField[]|null $field
	 */
	public function setField(?array $field):void {
	}

	/**
	 * @return PrototypeCompetencySearchCondition[]|null
	 */
	public function getCondition():?array {
		return ArrayHelper::getColumn($this->conditions, 'condition');
	}

	/**
	 * @param PrototypeCompetencySearchCondition[]|null $condition
	 */
	public function setCondition(?array $condition):void {
	}

	/**
	 * @return array
	 */
	public function getValue():array {
		return ArrayHelper::getColumn($this->conditions, 'value');
	}

	/**
	 * @param array $value
	 */
	public function setValue(array $value):void {
	}

}