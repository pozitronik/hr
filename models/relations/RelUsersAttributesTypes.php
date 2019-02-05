<?php
declare(strict_types = 1);

namespace app\models\relations;

/**
 * This is the model class for table "rel_users_attributes_types".
 *
 * @property int $user_attribute_id
 * @property int $type
 */
class RelUsersAttributesTypes extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rel_users_attributes_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_attribute_id', 'type'], 'required'],
			[['user_attribute_id', 'type'], 'integer'],
			[['user_attribute_id', 'type'], 'unique', 'targetAttribute' => ['user_attribute_id', 'type']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'user_attribute_id' => 'ID связки пользователь/атрибут',
			'type' => 'Тип',
		];
	}
}
