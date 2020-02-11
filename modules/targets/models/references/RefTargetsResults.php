<?php
declare(strict_types = 1);

namespace app\modules\targets\models\references;


use app\modules\references\models\CustomisableReference;
use app\modules\targets\models\Targets;

/**
 * @property int $id
 * @property string $name
 */
class RefTargetsResults extends CustomisableReference {
	public $menuCaption = 'Типы оценок целей';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_targets_results';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getUsedCount():int {
		return (int)Targets::find()->where(['result_type' => $this->id])->count();
	}


}
