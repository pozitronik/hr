<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use app\modules\dynamic_attributes\models\types\AttributePropertyInterface;
use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
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
	public $classArray = [];

	private function fill() {
		foreach ($this->_userScope as $user) {
			$userAttributes = $user->relDynamicAttributes;
			foreach ($userAttributes as $attributeKey => $userAttribute) {
				foreach ($userAttribute->properties as $propertyKey => $userAttributeProperty) {
					$userAttributeProperty->userId = $user->id;
					$this->dataArray[$userAttributeProperty->attributeId][$userAttributeProperty->id][] = $userAttributeProperty;
					$this->classArray[$userAttributeProperty->attributeId][$userAttributeProperty->id] = $userAttributeProperty::getTypeClass($userAttributeProperty->type);
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
	 * @return array
	 * @throws Throwable
	 */
	public function getAverage() {
		$averages = [];
		foreach ($this->dataArray as $attributeId => $propertyData) {
			/** @var DynamicAttributeProperty[] $userAttributePropertyArray */
			foreach ($propertyData as $propertyId => $userAttributePropertyArray) {
				$class = ArrayHelper::getValue($this->classArray, "{$attributeId}.{$propertyId}");
				if (null !== $value = $class::getAverageValue($userAttributePropertyArray)) {
					/** @var AttributePropertyInterface $classObject */
					$classObject = new $class;
					$classObject->setValue($value);
					$averages[$attributeId][$propertyId] = $classObject::viewField(['model' => $classObject, 'attribute' => 'property']);

				}

			}

		}
		return ($averages);
	}

}
