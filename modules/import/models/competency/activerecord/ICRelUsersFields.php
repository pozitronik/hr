<?php

namespace app\modules\import\models\competency\activerecord;

use Yii;

/**
 * This is the model class for table "import_competency_rel_users_fields".
 *
 * @property int $id
 * @property int $user_id Ключ к пользователю
 * @property int $field_id Ключ к полю атрибута
 * @property string $value Значение поля в сыром виде
 */
class ICRelUsersFields extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_competency_rel_users_fields';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'field_id'], 'required'],
			[['user_id', 'field_id'], 'integer'],
			[['value'], 'string'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'user_id' => 'Ключ к пользователю',
			'field_id' => 'Ключ к полю атрибута',
			'value' => 'Значение поля в сыром виде',
		];
	}
}
