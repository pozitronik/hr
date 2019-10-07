<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class DynamicAttributesPropertyCollection
 * Модель, содержащая набор свойств динамических атрибутов (с взаимными ссылками), и позволяющая производить над ними всякие извращения, допускаемые этими типами атрибутов (всякие агрегации)
 *
 * @package app\modules\dynamic_attributes\models
 * @property int $aggregation -- выбранный тип просматриваемой агрегации
 * @property bool $dropNullValues -- отсеивание пустых значений, если возможно
 */
class DynamicAttributesPropertyCollection extends Model {
	/** @var Users[] $_userScope */
	private $_userScope = [];
	private $_dataArray = [];
	private $_aggregation = DynamicAttributePropertyAggregation::AGGREGATION_AVG;
	private $_dropNullValues = false;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['aggregation'], 'integer'],
			[['dropNullValues'], 'boolean']
		];
	}

	/**
	 * @return int
	 */
	public function getAggregation():int {
		return $this->_aggregation;
	}

	/**
	 * @param int $aggregation
	 */
	public function setAggregation(int $aggregation):void {
		$this->_aggregation = $aggregation;
	}

	/**
	 * @return bool
	 */
	public function getDropNullValues():bool {
		return $this->_dropNullValues;
	}

	/**
	 * @param bool $dropNullValues
	 */
	public function setDropNullValues(bool $dropNullValues):void {
		$this->_dropNullValues = $dropNullValues;
	}

	private function fill():void {
		foreach ($this->_userScope as $user) {
			$userAttributes = $user->relDynamicAttributes;
			foreach ($userAttributes as $attributeKey => $userAttribute) {
				foreach ($userAttribute->properties as $propertyKey => $userAttributeProperty) {
					$userAttributeProperty->userId = $user->id;
					$this->_dataArray[$userAttributeProperty->attributeId][$userAttributeProperty->id]['values'][] = $userAttributeProperty;
					ArrayHelper::initValue($this->_dataArray, "{$userAttributeProperty->attributeId}.{$userAttributeProperty->id}.type", $userAttributeProperty->type);
				}
			}
		}
	}

	/**
	 * @param Users[] $userScope
	 */
	public function setUserScope(array $userScope):void {
		$this->_userScope = $userScope;
		$this->fill();
	}

	/**
	 * @return DynamicAttributes[]
	 * @throws Throwable
	 */
	public function applyAggregation():array {
		$aggregatedDynamicAttributes = [];
		foreach ($this->_dataArray as $attributeId => $propertyData) {
			/** @var DynamicAttributes $attributeModel */
			$attributeModel = DynamicAttributes::findModel($attributeId, new Exception("Can't load dynamic attribute!"));
			foreach ($propertyData as $propertyId => $userAttributePropertyArray) {
				$propertyClass = DynamicAttributeProperty::getTypeClass(ArrayHelper::getValue($userAttributePropertyArray, "type"));
				if (in_array($this->aggregation, $propertyClass::aggregationConfig()) && DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED !== $aggregatedValue = $propertyClass::applyAggregation(ArrayHelper::getValue($userAttributePropertyArray, 'values', []), $this->aggregation, $this->dropNullValues)) {
					$attributeModel->setVirtualProperty($propertyId, $aggregatedValue->value, $aggregatedValue->type);
				} else {
//					$attributeModel->setVirtualProperty($propertyId, (new $propertyClass())->value);//fill by empty attribute
					$attributeModel = null;//Пустое значение в массиве, в случае, если запрошенный агрегатор явно не поддерживается атрибутом
				}

			}
			$aggregatedDynamicAttributes[$attributeId] = $attributeModel;
		}
		return (ArrayHelper::filterValues($aggregatedDynamicAttributes, [null]));//убираем пустые значения
	}

}