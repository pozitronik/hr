<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\models\core\ActiveRecordExtended;
use app\models\relations\Relations;

/**
 * Class UsersIdentifiers
 * Модель для хранения и связей внешних идентификаторов пользователей (вводимыъ из внешних систем, через всякие импорты, апи и прочее).
 * Под каждый новый индефикатор заводим отдельную колонку.
 *
 * @package app\modules\users\models
 * @property int $id
 * @property int $user_id
 * @property string $tn -- табельник из ФОС
 */
class UsersIdentifiers extends ActiveRecordExtended {
	use Relations;

	/**
	 * @return array
	 */
	public function historyRules():array {
		//todo
		return [];
	}

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