<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\relations;

use yii\db\ActiveRecord;
use app\models\relations\Relations;

/**
 * This is the model class for table "rel_privileges_dynamic_rights".
 *
 * @property int id
 * @property int $privilege id привилегии
 * @property int $right id динамического права
 *
 */
class RelPrivilegesDynamicRights extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_privileges_dynamic_rights';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['privilege', 'right'], 'required'],
			[['id', 'privilege', 'right'], 'integer'],
			[['privilege', 'right'], 'unique', 'targetAttribute' => ['privilege', 'right']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'privilege' => 'Привилегия',
			'right' => 'Право'
		];
	}

}
