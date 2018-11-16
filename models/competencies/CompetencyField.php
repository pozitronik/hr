<?php
declare(strict_types = 1);

namespace app\models\competencies;

use yii\base\Model;

/**
 * Class CompetencyItem
 * Описание структуры поля компетенции
 * @package app\models\competencies
 *
 * @property string $name
 * @property string $type
 * @property boolean $required
 * @property boolean isNewRecord
 */
class CompetencyField extends Model {
	private $name = '';
	private $type = 'integer';
	private $required = false;
	private $isNewRecord = true;

	public const FIELD_TYPES = [
		'integer' => 'Число',
		'boolean' => 'Логический тип',
		'string' => 'Строка',
		'date' => 'Дата',
		'time' => 'Время',
		'range' => 'Интервал',
		'percent' => 'Проценты'
	];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['name', 'type'], 'string'],
			[['type'], 'in' , 'range' => array_keys(self::FIELD_TYPES)],//todo check this
			[['required'], 'boolean'],
			[['name', 'type', 'required'], 'required']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'name' => 'Название',
			'type' => 'Тип',
			'required' => 'Обязательное поле'
		];
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name):void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType():string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->type = $type;
	}

	/**
	 * @return bool
	 */
	public function getRequired():bool {
		return $this->required;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired(bool $required):void {
		$this->required = $required;
	}

	/**
	 * @return bool
	 */
	public function getIsNewRecord():bool {
		return $this->isNewRecord;
	}

}