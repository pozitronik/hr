<?php
declare(strict_types = 1);

namespace app\modules\salary\models\relations;

use app\models\core\ActiveRecordExtended;
use app\models\core\traits\ARExtended;
use app\models\relations\Relations;

/**
 * Class RelRefUserPositionsBranches
 * @package app\modules\references\models\relations
 */
class RelRefUserPositionsBranches extends ActiveRecordExtended {
	use Relations;
	use ARExtended;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'rel_ref_user_positions_branches';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['position_id', 'position_branch_id'], 'required'],
			[['position_id', 'position_branch_id'], 'integer'],
			[['position_id', 'position_branch_id'], 'unique', 'targetAttribute' => ['position_id', 'position_branch_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'position_id' => 'Должность',
			'position_branch_id' => 'Ветвь'
		];
	}

}