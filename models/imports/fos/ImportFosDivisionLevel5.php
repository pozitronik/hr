<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

/**
 * This is the model class for table "import_fos_division_level5".
 *
 * @property int $id
 * @property string $name
 */
class ImportFosDivisionLevel5 extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_division_level5';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}
}
