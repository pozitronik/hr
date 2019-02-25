<?php
declare(strict_types = 1);

namespace app\modules\grades\models\references;

use app\modules\references\models\Reference;

/**
 * Class RefUserPositionBranches
 * @package app\modules\references\models\refs
 */
class RefUserPositionBranches extends Reference {
	public $menuCaption = 'Ветки должностей';
	public $menuIcon = false;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_branches';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted'], 'integer'],
			[['name'], 'string', 'max' => 256]
		];
	}
}