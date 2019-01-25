<?php

namespace app\modules\import\models\competency\activerecord;

use Yii;

/**
 * This is the model class for table "import_competency_fields".
 *
 * @property int $id
 * @property int $attribute_id Ключ к атрибуту
 * @property string $name
 */
class ICFields extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_competency_fields';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['attribute_id', 'name'], 'required'],
			[['attribute_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'attribute_id' => 'Ключ к атрибуту',
			'name' => 'Name',
		];
	}
}
