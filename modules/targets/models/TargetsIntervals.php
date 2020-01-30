<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;

/**
 * This is the model class for table "sys_targets_intervals".
 *
 * @property int $id
 * @property int $target id цели
 * @property string|null $comment Описание интервала
 * @property string $create_date Дата создания
 * @property string|null $start_date Дата начала интервала
 * @property string|null $finish_date Дата конца интервала
 * @property int|null $start_quarter Начальный квартал [1..4]
 * @property int|null $finish_quarter Конечный квартал [1..4]
 * @property int|null $year Год
 * @property int|null $daddy ID зарегистрировавшего пользователя
 */
class TargetsIntervals extends ActiveRecordExtended {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_targets_intervals';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['target'], 'required'],
			[['target', 'daddy', 'start_quarter', 'finish_quarter', 'year'], 'integer'],
			[['target'], 'unique'],//на текущий момент у одной цели один интервал
			[['comment'], 'string'],
			[['create_date', 'start_date', 'finish_date'], 'safe'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target' => 'id цели',
			'comment' => 'Описание интервала',
			'create_date' => 'Дата создания',
			'start_date' => 'Дата начала интервала',
			'finish_date' => 'Дата конца интервала',
			'daddy' => 'ID зарегистрировавшего пользователя',
			'start_quarter' => 'Начальный квартал',
			'finish_quarter' => 'Конечный квартал',
			'year' => 'Год'
		];
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
			$result->createModel([
				'target' => $target_id,
				'start_quarter' => (int)mb_substr($period, 1, 1),
				'finish_quarter' => (int)mb_substr($period, 1, 1),
				'year_quarter' => 2020
			], false);

		} elseif (4 === mb_strlen($period)) {
			$result->createModel([
				'target' => $target_id,
				'start_quarter' => 1,
				'finish_quarter' => 4,
				'year_quarter' => $period
			], false);
		}
		return $result;
	}
}
