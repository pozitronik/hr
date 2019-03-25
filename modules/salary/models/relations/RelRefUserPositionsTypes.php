<?php
declare(strict_types = 1);

namespace app\modules\salary\models\relations;

use app\models\core\ActiveRecordExtended;
use app\models\relations\Relations;

/**
 * Class RelRefUserPositionsTypes
 * @package app\modules\references\models\relations
 */
class RelRefUserPositionsTypes extends ActiveRecordExtended {
	use Relations;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'rel_ref_user_positions_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['position_id', 'position_type_id'], 'required'],
			[['position_id', 'position_type_id'], 'integer'],
			[['position_id', 'position_type_id'], 'unique', 'targetAttribute' => ['position_id', 'position_type_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'position_id' => 'Должность',
			'position_type_id' => 'Тип'
		];
	}

}