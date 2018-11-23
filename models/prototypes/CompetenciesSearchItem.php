<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearch
 * Прототип модели поиска пользователей по компетенциям
 * @package app\models\prototypes
 *
 * @property boolean $logic Режим поиска: true - И (все правила), false - ИЛИ (хотя бы одно правило)
 * @property integer|null $competency Искомая компетенция (id)
 * @property integer|null $field Поле компетенции (id)
 * @property string|null $condition Условие поиска
 * @property mixed|null $value Искомое значение
 */
class CompetenciesSearchItem extends Model {
	private $logic = true;//todo rename
	private $competency;
	private $field;
	private $condition;
	private $value;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['logic'], 'boolean'],
			[['competency', 'field'], 'integer'],
			[['condition', 'value'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'logic' => 'Объединение',
			'competency' => 'Компетенция',
			'field' => 'Поле',
			'condition' => 'Условие',
			'value' => 'Значение'
		];
	}

	/**
	 * @return bool
	 */
	public function getLogic():bool {
		return $this->logic;
	}

	/**
	 * @param bool $logic
	 */
	public function setLogic(bool $logic):void {
		$this->logic = $logic;
	}

	/**
	 * @return int|null
	 */
	public function getCompetency():?int {
		return $this->competency;
	}

	/**
	 * @param int|null $competency
	 */
	public function setCompetency($competency):void {
		$this->competency = is_numeric($competency)?(int)$competency:null;
	}

	/**
	 * @return int|null
	 */
	public function getField():?int {
		return $this->field;
	}

	/**
	 * @param int|null $field
	 */
	public function setField($field):void {
		$this->field = is_numeric($field)?(int)$field:null;
	}

	/**
	 * @return string|null
	 */
	public function getCondition():?string {
		return $this->condition;
	}

	/**
	 * @param string|null $condition
	 */
	public function setCondition(?string $condition):void {
		$this->condition = $condition;
	}

	/**
	 * @return mixed|null
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param mixed|null $value
	 */
	public function setValue($value):void {
		$this->value = $value;
	}

}