<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\components\pozitronik\references\models\CustomisableReference;

/**
 * Справочник премиальных групп. Премиальная группа применяется, как модификатор при указании зарплатной вилки
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 */
class RefSalaryPremiumGroups extends CustomisableReference {
	public $menuCaption = 'Премиальные группы';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_salary_premium_group';
	}

}
