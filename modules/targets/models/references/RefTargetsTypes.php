<?php
declare(strict_types = 1);

namespace app\modules\targets\models\references;


use app\modules\references\models\CustomisableReference;

/**
 * @property int $id
 * @property string $name
 */
class RefTargetsTypes extends CustomisableReference {
	public $menuCaption = 'Типы целей';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_targets_types';
	}
}
