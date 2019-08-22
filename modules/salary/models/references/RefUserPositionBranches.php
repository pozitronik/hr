<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\references\models\Reference;

/**
 * Справочник веток должностей. Ветка должности - необязательный, не влияющий ни на что атрибут должности.
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
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['name', 'color', 'textcolor'], 'string', 'max' => 256],
		];
	}

}