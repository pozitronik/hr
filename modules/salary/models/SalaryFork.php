<?php
declare(strict_types = 1);

namespace app\modules\salary\models;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "salary_fork".
 *
 * @property int $id
 * @property int $position_id Должность
 * @property int $grade_id Грейд
 * @property int $premium_group_id Группа премирования
 * @property int $location_id Локация
 * @property double $min Минимальный оклад
 * @property double $max Максимальный оклад
 * @property int $currency Валюта
 * @property bool $deleted
 *
 * @property-read double $mid Средний оклад
 * @property RefUserPositions|ActiveQuery $refUserPosition
 * @property RefGrades|ActiveQuery $refGrade
 * @property RefSalaryPremiumGroups|ActiveQuery|null $refPremiumGroup
 * @property RefLocations|ActiveQuery|null $refLocation
 */
class SalaryFork extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'salary_fork';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['position_id', 'grade_id', 'min', 'max'], 'required'],
			[['position_id', 'grade_id', 'premium_group_id', 'location_id', 'currency'], 'integer'],
			[['premium_group_id', 'location_id'], 'default', 'value' => null],//для корректного отрабатывания unique-валидатора: при сохранении пустые поля передаются как пустые строки, БД же ждёт именно пустое значение
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['min', 'max'], 'number'],
			[['max'], function():bool {
				if ($this->min > $this->max) {
					$this->addError('max', 'Максимальный оклад не может быть меньше минимального');
					return false;
				}
				return true;
			}],
			[['position_id', /*'grade_id', 'premium_group_id', 'location_id'*/], 'unique', 'targetAttribute' => ['position_id', 'grade_id', 'premium_group_id', 'location_id'], 'message' => 'Зарплатная вилка с такой конфигурацией уже существует'],
			/*[['position_id'], function($attribute) {
			//оставлю на всякий случай: не все БД одинаково трактуют NULL в выборках
				return 0 === (int)self::find()->where(['position_id' => $this->position_id, 'grade_id' => $this->grade_id, 'premium_group_id' => $this->premium_group_id, 'location_id' => $this->location_id])->count();
			}]*/
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'position_id' => 'Должность',
			'grade_id' => 'Грейд',
			'premium_group_id' => 'Группа премирования',
			'location_id' => 'Расположение',
			'min' => 'Минимальный оклад',
			'max' => 'Максимальный оклад',
			'mid' => 'Средний оклад',
			'currency' => 'Валюта'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefUserPosition():ActiveQuery {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefGrade():ActiveQuery {
		return $this->hasOne(RefGrades::class, ['id' => 'grade_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefPremiumGroup():ActiveQuery {
		return $this->hasOne(RefSalaryPremiumGroups::class, ['id' => 'premium_group_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefLocation():ActiveQuery {
		return $this->hasOne(RefLocations::class, ['id' => 'location_id']);
	}

	/**
	 * @return float
	 */
	public function getMid():float {
		return ($this->max + $this->min) / 2;
	}

	/**
	 * @return string
	 */
	public function __toString():string {
		return $this->attributeLabels()['min'].": ".$this->min.", ".$this->attributeLabels()['max'].": ".$this->max;
	}
}
