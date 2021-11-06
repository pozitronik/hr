<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use yii\base\Model;

/**
 * @property boolean $union Режим объединения поиска: true - И (все правила), false - ИЛИ (хотя бы одно правило)
 * @property int[]|null $type Массив типов отношений атрибута (id)
 * @property int|null $attribute Искомый атрибут (id)
 * @property int|null $property Свойство атрибута (id)
 * @property string|null $condition Условие поиска
 * @property mixed|null $value Искомое значение
 */
class DynamicAttributesSearchItem extends Model {
	private $union = true;
	private $type;
	private $attribute;
	private $property;
	private $condition;
	private $value;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['union'], 'boolean'],
			[['attribute', 'property'], 'integer'],
			[['condition', 'value', 'type'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'union' => 'Объединение',
			'type' => 'Тип отношения атрибута',
			'attribute' => 'Атрибут',
			'property' => 'Свойство',
			'condition' => 'Условие',
			'value' => 'Значение'
		];
	}

	/**
	 * @return bool
	 */
	public function getUnion():bool {
		return $this->union;
	}

	/**
	 * @param bool $union
	 */
	public function setUnion(bool $union):void {
		$this->union = $union;
	}

	/**
	 * @return int|null
	 */
	public function getAttribute():?int {
		return $this->attribute;
	}

	/**
	 * @param int|null $attribute
	 */
	public function setAttribute(?int $attribute):void {
		$this->attribute = is_numeric($attribute)?(int)$attribute:null;
	}

	/**
	 * @return int|null
	 */
	public function getProperty():?int {
		return $this->property;
	}

	/**
	 * @param int|null $property
	 */
	public function setProperty(?int $property):void {
		$this->property = is_numeric($property)?(int)$property:null;
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
	public function setValue(mixed $value):void {
		$this->value = $value;
	}

	/**
	 * @return int[]|mixed|null
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int[]|null $type
	 */
	public function setType(?array $type):void {
		$this->type = $type;
	}

}