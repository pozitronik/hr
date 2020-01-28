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
 * @property int|null $start_quarter Начальный квартал
 * @property int|null $finish_quarter Конечный квартал
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
			'daddy' => 'ID зарегистрировавшего пользователя'
		];
	}
}
