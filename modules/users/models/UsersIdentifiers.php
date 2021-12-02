<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\components\pozitronik\core\traits\ARExtended;
use app\components\pozitronik\core\traits\Relations;
use yii\db\ActiveRecord;

/**
 * Class UsersIdentifiers
 * Модель для хранения и связей внешних идентификаторов пользователей (вводимых из внешних систем, через всякие импорты, апи и прочее).
 * Под каждый новый идентификатор заводим отдельную колонку.
 *
 * @package app\modules\users\models
 * @property int $id
 * @property int $user_id
 * @property string $tn -- табельник из ФОС
 */
class UsersIdentifiers extends ActiveRecord {
	use Relations;
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_users_identifiers';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id'], 'required'],
			[['id', 'user_id'], 'integer'],
			[['tn'], 'safe'],
			[['user_id', 'tn'], 'unique'],
			[['user_id', 'tn'], 'unique', 'targetAttribute' => ['user_id', 'tn']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'Сотрудник',
			'tn' => 'Табельный номер'
		];
	}
}