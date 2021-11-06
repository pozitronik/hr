<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_chapter".
 *
 * @property int $chapter_id
 * @property string $code
 * @property string $name
 * @property int $leader_id key to chapter leader id
 * @property int $couch_id key to couch id
 * @property int $domain
 * @property null|int $hr_group_id
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
			['chapter_id', 'integer'],
			['chapter_id', 'unique'],
			['chapter_id', 'required'],
			[['leader_id', 'couch_id'], 'integer'],
			[['code', 'name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required'],
			['hr_group_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'chapter_id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'leader_id' => 'key to chapter leader id',
			'couch_id' => 'key to couch id'
		];
	}
}
