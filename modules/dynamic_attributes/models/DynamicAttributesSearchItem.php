<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use yii\base\Model;

/**
 * @property boolean $union Режим объединения поиска: true - И (все правила), false - ИЛИ (хотя бы одно правило)
 * @property integer|null $attribute Искомый атрибут (id)
 * @property integer|null $property Свойство атрибута (id)
 * @property string|null $condition Условие поиска
 * @property mixed|null $value Искомое значение
 */
class DynamicAttributesSearchItem extends Model {
	private $union = true;
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
			[['condition', 'value'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'union' => 'Объединение',
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
	public function setAttribute($attribute):void {
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
	public function setProperty($property):void {
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
	public function setValue($value):void {
		$this->value = $value;
	}

}