<?php
declare(strict_types = 1);

namespace app\modules\groups;

use app\components\pozitronik\core\models\core_module\CoreModule;

/**
 * Class GroupsModule
 * @package app\modules\groups
 *
 * @property string $name
 */
class GroupsModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Поддержка групп';
	}
}
