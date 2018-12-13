<?php
declare(strict_types = 1);

namespace app\models\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_attributes".
 *
 * @property int $id
 * @property int $user_id
 * @property int $attribute_id
 */
class RelUsersAttributes extends ActiveRecord {
	use Relations;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_attributes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'attribute_id'], 'required'],
			[['user_id', 'attribute_id'], 'integer'],
			[['user_id', 'attribute_id'], 'unique', 'targetAttribute' => ['user_id', 'attribute_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'attribute_id' => 'Attribute ID'
		];
	}
}
