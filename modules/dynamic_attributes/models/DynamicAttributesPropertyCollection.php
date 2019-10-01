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
 */
class DynamicAttributesPropertyCollection extends Model {
	/** @var Users[] $_userScope */
	private $_userScope = [];
	public $dataArray = [];

	private function fill() {
		foreach ($this->_userScope as $user) {
			$userAttributes = $user->relDynamicAttributes;
			foreach ($userAttributes as $attributeKey => $userAttribute) {
				foreach ($userAttribute->properties as $propertyKey => $userAttributeProperty) {
					$userAttributeProperty->userId = $user->id;
					$this->dataArray[$userAttributeProperty->attributeId][$userAttributeProperty->id]['values'][] = $userAttributeProperty;
					ArrayHelper::initValue($this->dataArray, "{$userAttributeProperty->attributeId}.{$userAttributeProperty->id}.type", $userAttributeProperty->type);
				}
			}
		}
	}

	/**
	 * @param Users[] $userScope
	 */
	public function setUserScope(array $userScope) {
		$this->_userScope = $userScope;
		$this->fill();
	}

	/**
	 * @return DynamicAttributes[]
	 * @throws Throwable
	 */
	public function getAverage() {
		$averages = [];
		foreach ($this->dataArray as $attributeId => $propertyData) {
			/** @var DynamicAttributes $attributeModel */
			$attributeModel = DynamicAttributes::findModel($attributeId, new Exception("Can't load dynamic attribute!"));
			/** @var DynamicAttributeProperty[] $userAttributePropertyArray */
			foreach ($propertyData as $propertyId => $userAttributePropertyArray) {
				$propertyClass = DynamicAttributeProperty::getTypeClass(ArrayHelper::getValue($this->dataArray, "{$attributeId}.{$propertyId}.type"));
				if (null !== $value = $propertyClass::getAverageValue($userAttributePropertyArray)) {
					$attributeModel->setVirtualProperty($propertyId, $value);
				}

			}
			$averages[$attributeId] = $attributeModel;

		}
		return ($averages);
	}

}