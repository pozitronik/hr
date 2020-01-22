<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\user\CurrentUser;

/**
 * Class Targets
 *
 * @property int $id
 * @property int $type
 * @property int $result_type
 * @property int $group_id
 * @property string $name
 * @property string $comment
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property boolean $deleted Флаг удаления
 */
class Targets extends ActiveRecordExtended {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_targets';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['type', 'group_id', 'name', 'create_date'], 'required'],
			[['type', 'result_type', 'group_id', 'daddy'], 'integer'],
			[['name'], 'string', 'max' => 512],
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'type' => 'Тип цели',
			'result_type' => 'Тип результата цели',
			'group_id' => 'Группа',
			'name' => 'Название',
			'comment' => 'Описание',
			'create_date' => 'Дата создания',
			'daddy' => 'Создатель',
			'deleted' => 'Флаг удаления'
		];
	}

}