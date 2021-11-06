<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\components\pozitronik\core\traits\ARExtended;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Прототипирую период исполнения цели + связанные методы
 * Class TargetsPeriods
 * @package app\modules\targets\models
 *
 *
 * @property int $id
 * @property int $target_id
 *
 * @property bool $q1 -- Цель на первый квартал
 * @property bool $q2 -- ...второй квартал
 * @property bool $q3 -- ...третий квартал
 * @property bool $q4 -- ...четвёртый квартал
 * @property bool $is_year -- годовая цель
 *
 * @property Targets $relTargets
 * @property-read bool $notSet
 * @property-read string[] $asFilePeriod -- Строковое представление периодов цели, сделано геттером, @see modules/targets/views/targets/index.php:158
 */
class TargetsPeriods extends ActiveRecord {
	use ARExtended;

	public const PERIOD_YEAR = 0;
	public const PERIOD_NOT_SET = -1;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_targets_periods';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['target_id'], 'required'],
			[['target_id', 'id'], 'integer'],
			[['target_id'], 'unique'],//на текущий момент у одной цели один интервал
			[['q1', 'q2', 'q3', 'q4', 'is_year'], 'boolean'],
			[['q1', 'q2', 'q3', 'q4', 'is_year'], 'default', 'value' => false]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target_id' => 'Цель',
			'q1' => 'Первый квартал',
			'q2' => 'Второй квартал',
			'q3' => 'Третий квартал',
			'q4' => 'Четвёртый квартал',
			'is_year' => 'Годовая цель',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargets():ActiveQuery {
		return $this->hasOne(Targets::class, ['id' => 'target_id']);
	}

	/**
	 * Из вариантов, приведённых в файле импорта пытаемся создать период
	 * @param string $period -- может быть в формате Q1 - Q4 или цифра года
	 * @param int $target_id
	 * @return static
	 * @throws Exception
	 */
	public static function fromFilePeriod(string $period, int $target_id):self {
		$result = new self();
		if (2 === mb_strlen($period)) {
			$qn = (int)mb_substr($period, 1, 1);
			$result->createModel([
				'target_id' => $target_id,
				"q{$qn}" => true
			]);

		} elseif (4 === mb_strlen($period)) {
			$result->createModel([
				'target_id' => $target_id,
				'is_year' => true
			]);
		}
		return $result;
	}

	/**
	 * @return bool
	 */
	public function getNotSet():bool {
		return !($this->q1 || $this->q2 || $this->q3 || $this->q4 || $this->is_year);
	}

	/**
	 * Строковое представление периодов цели
	 * @return string[]
	 */
	public function getAsFilePeriod():array {
		$result = [];
		foreach (['q1', 'q2', 'q3', 'q4', 'is_year'] as $value) {
			if (true === (bool)$this->{$value}) {
				$result[] = $this->attributeLabels()[$value];
			}
		}
		return $result;
	}

}