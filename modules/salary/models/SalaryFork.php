<?php
declare(strict_types = 1);

namespace app\modules\salary\models;

use app\helpers\ArrayHelper;
use app\models\core\LCQuery;
use app\models\core\StrictInterface;
use app\models\core\traits\ARExtended;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\widgets\alert\AlertModel;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

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
 * @property RefUserPositions|ActiveQuery $refUserPosition //todo: По логике приложения нуно переименовать в relUserPosition И далее
 * @property RefGrades|ActiveQuery $refGrade
 * @property RefSalaryPremiumGroups|ActiveQuery|null $refPremiumGroup
 * @property RefLocations|ActiveQuery|null $refLocation
 */
class SalaryFork extends ActiveRecord implements StrictInterface {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'salary_fork';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
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
			'location_id' => 'Локация',
			'min' => 'Минимальный оклад',
			'max' => 'Максимальный оклад',
			'mid' => 'Средний оклад',
			'currency' => 'Валюта'
		];
	}

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRefUserPosition() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position_id']);
	}

	/**
	 * @return RefGrades|ActiveQuery
	 */
	public function getRefGrade() {
		return $this->hasOne(RefGrades::class, ['id' => 'grade_id']);
	}

	/**
	 * @return RefSalaryPremiumGroups|ActiveQuery|null
	 */
	public function getRefPremiumGroup() {
		return $this->hasOne(RefSalaryPremiumGroups::class, ['id' => 'premium_group_id']);
	}

	/**
	 * @return RefLocations|ActiveQuery|null
	 */
	public function getRefLocation() {
		return $this->hasOne(RefLocations::class, ['id' => 'location_id']);
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 * @throws Exception
	 */
	public function createModel(?array $paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					$this->refresh();
					AlertModel::SuccessNotify();
					return true;
				}
			}
			AlertModel::ErrorsNotify($this->errors);
		}

		$transaction->rollBack();
		return false;
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function updateModel(?array $paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				$this->refresh();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
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
