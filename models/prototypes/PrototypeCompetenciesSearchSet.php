<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use app\models\core\SysExceptions;
use ReflectionClass;
use Throwable;
use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearchSet
 * @package app\models\prototypes
 *
 * @property PrototypeCompetenciesSearch[] $set
 *
 *
 *
 * @property boolean[] $logic;
 * @property Competencies[]|null[] $competency;
 * @property CompetencyField[]|null $field;
 * @property PrototypeCompetencySearchCondition[]|null $condition;
 * @property string[]|null[] $value
 *
 * @property $removeCondition
 * @property null|string $classNameShort
 * @property $addCondition
 */
class PrototypeCompetenciesSearchSet extends Model {
	private $set = [];

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	public function getClassNameShort():?string {
		try {
			return (new ReflectionClass($this))->getShortName();
		} catch (Throwable $t) {
			SysExceptions::log($t, $t);
		}
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['set', 'logic', 'competency', 'field', 'condition', 'value'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'set' => 'Набор условий',
			'logic' => 'Объединение',
			'competency' => 'Компетенция',
			'field' => 'Поле',
			'condition' => 'Условие',
			'value' => 'Значение'
		];
	}

	public function init():void {
		parent::init();
		if ([] === $this->set) $this->set[] = new PrototypeCompetenciesSearch();//По умолчанию одно условие
	}

	/**
	 * @param PrototypeCompetenciesSearch|null $condition
	 */
	public function addItem(PrototypeCompetenciesSearch $condition = null):void {
		if (null === $condition) $condition = new PrototypeCompetenciesSearch();
		$this->set[] = $condition;
	}

	/**
	 * @param int $index
	 */
	public function removeItem(int $index):void {
		ArrayHelper::remove($this->set, $index);
	}

	/**
	 * @return PrototypeCompetenciesSearch[]
	 */
	public function getSet():array {
		return $this->set;
	}

	/**
	 * @param int $count
	 */
	private function initSet(int $count):void {
		/** @noinspection ForeachInvariantsInspection */
		for ($i = 0; $i < $count; $i++) {
			if (!isset($this->set[$i])) $this->set[$i] = new PrototypeCompetenciesSearch();
		}
	}

	/**
	 * Для предвыбранной компетенции нужно отдать её поля
	 * @param integer $index
	 */
	public function competencyFields($index):array {
		if (false !== $competency = Competencies::findModel($index)) {
			return ArrayHelper::map($competency->structure, 'id', 'name');
		}
		return [];
	}

	/**
	 * Для предвыбранной компетенции нужно отдать её поля
	 */
	public function fieldsConditions($competencyIndex, $fieldIndex) {
		if (false !== $competency = Competencies::findModel($competencyIndex)) {
			$field = $competency->structure[$fieldIndex];

			$type = $field['type'];
			return PrototypeCompetencySearchCondition::findCondition($type);
		}
		return [];
	}

	/******************************************/

	/**
	 * @return boolean[]
	 */
	public function getLogic():array {
		return ArrayHelper::getColumn($this->set, 'logic');
	}

	/**
	 * @param boolean[] $logic
	 */
	public function setLogic(array $logic):void {
		self::initSet(count($logic));
		foreach ($logic as $index => $value) {
			$this->set[$index]->logic = $value;
		}
	}

	/**
	 * @return Competencies[]|null[]
	 */
	public function getCompetency():?array {
		return ArrayHelper::getColumn($this->set, 'competency');
	}

	/**
	 * @param Competencies[]|null[] $competency
	 */
	public function setCompetency(?array $competency):void {
		self::initSet(count($competency));
		foreach ($competency as $index => $value) {
			$this->set[$index]->competency = $value;
		}
	}

	/**
	 * @return CompetencyField[]|null
	 */
	public function getField():?array {
		return ArrayHelper::getColumn($this->set, 'field');
	}

	/**
	 * @param CompetencyField[]|null $field
	 */
	public function setField(?array $field):void {
		self::initSet(count($field));
		foreach ($field as $index => $value) {
			$this->set[$index]->field = $value;
		}
	}

	/**
	 * @return PrototypeCompetencySearchCondition[]|null
	 */
	public function getCondition():?array {
		return ArrayHelper::getColumn($this->set, 'condition');
	}

	/**
	 * @param PrototypeCompetencySearchCondition[]|null $condition
	 */
	public function setCondition(?array $condition):void {
		self::initSet(count($condition));
		foreach ($condition as $index => $value) {
			$this->set[$index]->condition = $value;
		}
	}

	/**
	 * @return array
	 */
	public function getValue():array {
		return ArrayHelper::getColumn($this->set, 'value');
	}

	/**
	 * @param null[]|string[] $value
	 */
	public function setValue(?array $values):void {
		self::initSet(count($values));
		foreach ($values as $index => $value) {
			$this->set[$index]->value = $value;
		}
	}

}