<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_chapter".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $leader_id key to chapter leader id
 * @property int $couch_id key to couch id
 */
class ImportFosChapter extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_chapter';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['leader_id', 'couch_id'], 'integer'],
			[['code', 'name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'leader_id' => 'key to chapter leader id',
			'couch_id' => 'key to couch id'
		];
	}
}
