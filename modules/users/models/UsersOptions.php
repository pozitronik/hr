<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_users_options".
 *
 * @property int $user_id System user id
 * @property string $option Option name
 * @property array $value Option value in JSON
 */
class UsersOptions extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_users_options';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'user_id'], 'integer'],
			[['option'], 'required'],
			[['value'], 'safe'],
			[['option'], 'string', 'max' => 32],
			[['user_id', 'option'], 'unique', 'targetAttribute' => ['user_id', 'option']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'user_id' => 'System user id',
			'option' => 'Option name',
			'value' => 'Option value in JSON'
		];
	}

}
