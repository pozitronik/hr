<?php
declare(strict_types = 1);

namespace app\models\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_privileges_rights".
 *
 * @property int id
 * @property int $privilege id привилегии
 * @property string $right название класса права
 *
 */
class RelPrivilegesRights extends ActiveRecord {
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
