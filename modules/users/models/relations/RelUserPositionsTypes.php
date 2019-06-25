<?php
declare(strict_types = 1);

namespace app\modules\users\models\relations;

use app\models\core\ActiveRecordExtended;
use app\models\relations\Relations;

/**
 * Class RelRefUserPositionsTypes
 * @package app\modules\references\models\relations
 */
class RelUserPositionsTypes extends ActiveRecordExtended {
	use Relations;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'rel_user_position_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'position_type_id'], 'required'],
			[['user_id', 'position_type_id'], 'integer'],
			[['user_id', 'position_type_id'], 'unique', 'targetAttribute' => ['user_id', 'position_type_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'position_id' => 'Пользователь',
			'position_type_id' => 'Тип должности'
		];
	}

}