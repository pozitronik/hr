<?php
declare(strict_types = 1);

namespace app\modules\groups;

use app\models\core\core_module\CoreModule;

/** @noinspection EmptyClassInspection */

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
