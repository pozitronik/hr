<?php
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\Csv;
use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
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
		$competency = '';
		$type = 'percent';
		$transaction = Yii::$app->db->beginTransaction();
		try {
			foreach ($array as $row) {
				$competency = empty($row[0])?$competency:$row[0];
				$field = $row[1];
				$this->addCompetency($competency, $field, $type);
			}
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();
	}

	/**
	 * @param string $competencyName
	 * @param string $fieldName
	 * @param string $typeName
	 * @throws Throwable
	 * @throws Exception
	 */
	public function addCompetency(string $competencyName, string $fieldName, string $typeName):void {
		if (null === $competency = Competencies::find()->where(['name' => $competencyName])->one()) {
			$competency = new Competencies();
			$competency->createCompetency(['name' => $competencyName, 'category' => 0]);
		}
		if (null === $field = $competency->getFieldByName($fieldName)) {
			$field = new CompetencyField([
				'competencyId' => $competency->id,
				'name' => $fieldName,
				'type' => $typeName
			]);
			$field->id = $competency->setField($field, null);
		}

	}
}