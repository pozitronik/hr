<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $domain
 * @property null|int $hr_group_id
 */
abstract class ImportBeelineBase extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[['name'], 'required'],
			['domain', 'integer'], ['domain', 'required'],
			['hr_group_id', 'integer']
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
