<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\components\pozitronik\references\models\CustomisableReference;

/**
 * Справочник расположений. Расположение применяется, как модификатор при задании зарплатной вилки.
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 *
 * @property string $color
 *
 */
class RefLocations extends CustomisableReference {
	public $menuCaption = 'Локации';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_locations';
	}

}
