<?php
declare(strict_types = 1);

namespace app\modules\references\models\refs;

use app\modules\references\models\Reference;

/**
 * Class RefUserPositionTypes
 * @package app\modules\references\models\refs
 */
class RefUserPositionTypes extends Reference {
	public $menuCaption = 'Типы должностей';
	public $menuIcon = false;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_types';
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