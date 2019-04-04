<?php
declare(strict_types = 1);

namespace app\modules\privileges\models\relations;

use app\models\core\ActiveRecordExtended;
use app\models\relations\Relations;

/**
 * This is the model class for table "rel_privileges_rights".
 *
 * @property int id
 * @property int $privilege id привилегии
 * @property string $right название класса права
 *
 */
class RelPrivilegesRights extends ActiveRecordExtended {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_privileges_rights';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['privilege', 'right'], 'required'],
			[['id', 'privilege'], 'integer'],
			[['right'], 'string', 'max' => 256],
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