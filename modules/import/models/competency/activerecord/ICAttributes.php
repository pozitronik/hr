<?php

namespace app\modules\import\models\competency\activerecord;

use Yii;

/**
 * This is the model class for table "import_competency_attributes".
 *
 * @property int $id
 * @property string $name Название атрибута
 * @property int $hr_attribute_id id в системе
 */
class ICAttributes extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_competency_attributes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name'], 'required'],
			[['hr_attribute_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название атрибута',
			'hr_attribute_id' => 'id в системе',
		];
	}
}
