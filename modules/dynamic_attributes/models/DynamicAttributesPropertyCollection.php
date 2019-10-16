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
 * @property int|null $aggregation -- выбранный тип просматриваемой агрегации
 * @property int|null $attributeId -- выбранный атрибут
 * @property int|null $propertyId -- выбранное свойство выбранного атрибута
 * @property bool $dropNullValues -- отсеивание пустых значений, если возможно
 * @property-read int[] $scopeAttributes -- id всех атрибутов в скоупе пользователей
 * @property-read array $scopeAttributesLabels -- массив id=>label для атрибутов в скоупе (для выбиралок)
 * @property-read int[] $scopeAggregations -- id всех агрегаций, поддерживаемых атрибутами в скоупе
 * @property-read array $scopeAggregationsLabels -- массив id=>label для агрегаций, поддерживаемых атрибутами в скоупе
 */
class DynamicAttributesPropertyCollection extends Model {
	/** @var Users[] $_userScope */
	private $_userScope = [];
	private $_dataArray = [];
	private $_aggregation = DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
	private $_attributeId;
	private $_propertyId;
	private $_dropNullValues = false;

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['aggregation', 'attributeId', 'propertyId'], 'integer'],
			[['aggregation'], 'required'],
			[['dropNullValues'], 'boolean']
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels() {
		return [
			'aggregation' => 'Тип статистики',
			'attributeId' => 'Атрибут',
			'propertyId' => 'Свойство',
			'dropNullValues' => 'Отбросить пустые значения'
		];
	}

	/**
	 * @return int
	 */
	public function getAggregation():?int {
		return empty($this->_aggregation)?null:$this->_aggregation;
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

	public function fill():void {
		foreach ($this->_userScope as $user) {
			$userAttributes = (null === $this->attributeId)?$user->relDynamicAttributes:[DynamicAttributes::findModel($this->attributeId)];
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
	}

	/**
	 * Возвращает массив атрибутов, содержащий агрегированые свойства по заданному скоупу
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
					$attributeModel->setVirtualProperty($propertyId, 'Не поддерживается', DynamicAttributeProperty::PROPERTY_UNSUPPORTED);//fill by empty attribute
				}

			}
			/*Если все свойства атрибута не поддерживаются, то вместо атрибута пишем null, он отфильтруется*/
			$aggregatedDynamicAttributes[$attributeId] = (count(ArrayHelper::filterValues(ArrayHelper::getColumn($attributeModel->getVirtualProperties(), 'type'), [DynamicAttributeProperty::PROPERTY_UNSUPPORTED])) > 0)?$attributeModel:null;

		}
		return (ArrayHelper::filterValues($aggregatedDynamicAttributes, [null]));//убираем пустые значения
	}

	/**
	 * @return int[]
	 */
	public function getScopeAttributes():array {
		return array_keys($this->_dataArray);
	}

	/**
	 * @return int[]
	 */
	public function getScopeAggregations():array {
		$aggregations = [];
		foreach ($this->_dataArray as $attributeId => $propertyData) {
			/** @var DynamicAttributes $attributeModel */
			foreach ($propertyData as $propertyId => $userAttributePropertyArray) {
				$propertyClass = DynamicAttributeProperty::getTypeClass(ArrayHelper::getValue($userAttributePropertyArray, "type"));
				$aggregations[] = $propertyClass::aggregationConfig();
			}
		}
		return array_unique(array_merge([], ...$aggregations));
	}

	/**
	 * @return int|null
	 */
	public function getAttributeId():?int {
		return empty($this->_attributeId)?null:(int)$this->_attributeId;
	}

	/**
	 * @param int|null $attributeId
	 */
	public function setAttributeId($attributeId):void {
		$this->_attributeId = $attributeId;
	}

	/**
	 * @return int|null
	 */
	public function getPropertyId():?int {
		return empty($this->_propertyId)?null:(int)$this->_propertyId;
	}

	/**
	 * @param int|null $propertyId
	 */
	public function setPropertyId($propertyId):void {
		$this->_propertyId = $propertyId;
	}

	/**
	 * @return array
	 */
	public function getScopeAggregationsLabels():array {
		return array_intersect_key(DynamicAttributePropertyAggregation::AGGREGATION_LABELS, array_flip($this->scopeAggregations));
	}

	/**
	 * @return array
	 */
	public function getScopeAttributesLabels():array {
		$attributes = DynamicAttributes::findModels($this->scopeAttributes);
		return ArrayHelper::map($attributes, 'id', 'name');
	}

}