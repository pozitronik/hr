<?php
declare(strict_types = 1);

namespace app\models\imports\old;

use app\helpers\Csv;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class CompetencyRecord
 * @package app\models\imports
 * Простой одноразовый импорт списка компетенций
 */
class CompetencyRecord extends Model {
	/**
	 * @param string $filename
	 * @throws Exception
	 */
	public function importRecords(string $filename):void {
		$array = Csv::csvToArray($filename);
		$attribute = '';
		$type = 'percent';
		$transaction = Yii::$app->db->beginTransaction();
		try {
			foreach ($array as $row) {
				$attribute = empty($row[0])?$attribute:$row[0];
				$field = $row[1];
				$this->addCompetency($attribute, $field, $type);
			}
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();
	}

	/**
	 * @param string $attributeName
	 * @param string $fieldName
	 * @param string $typeName
	 * @throws Throwable
	 * @throws Exception
	 */
	public function addCompetency(string $attributeName, string $fieldName, string $typeName):void {
		if (null === $attribute = DynamicAttributes::find()->where(['name' => $attributeName])->one()) {
			$attribute = new DynamicAttributes();
			$attribute->createAttribute(['name' => $attributeName, 'category' => 0]);
		}
		if (null === $property = $attribute->getPropertyByName($fieldName)) {
			$property = new DynamicAttributeProperty([
				'attributeId' => $attribute->id,
				'name' => $fieldName,
				'type' => $typeName
			]);
			$property->id = $attribute->setProperty($property, null);
		}

	}
}