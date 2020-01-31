<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\models\core\traits\ARExtended;
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
 * @property bool $isYear -- годовая цель
 *
 * @property Targets $relTargets
 */
class TargetsPeriods extends ActiveRecord {
	use ARExtended;

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
	 * @return Targets|ActiveQuery
	 */
	public function getRelTargets() {
		return $this->hasOne(Targets::class, ['id' => 'target_id']);
	}

	/**
	 * Из вариантов, приведённых в файле импорта пытаемся создать период
	 * @param string $period -- может быть в формате Q1 - Q4 или цифра года
	 * @param int $target_id
	 * @return static
	 */
	public static function fromFilePeriod(string $period, int $target_id):self {
		$result = new self();
		if (2 === mb_strlen($period)) {
			$qn = (int)mb_substr($period, 1, 1);
			$result->createModel([
				'target_id' => $target_id,
				"q{$qn}" => true
			], false);

		} elseif (4 === mb_strlen($period)) {
			$result->createModel([
				'target' => $target_id,
				'is_year' => true
			], false);
		}
		return $result;
	}
}