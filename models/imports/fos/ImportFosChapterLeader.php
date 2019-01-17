<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

/**
 * This is the model class for table "import_fos_chapter_leader".
 *
 * @property int $id
 * @property int $user_id key to user id
 */
class ImportFosChapterLeader extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_chapter_leader';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id'], 'required'],
			[['user_id'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'key to user id'
		];
	}
}
