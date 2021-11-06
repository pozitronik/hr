<?php
declare(strict_types = 1);

namespace app\modules\targets;

use app\components\pozitronik\core\models\core_module\CoreModule;

/**
 * Class TargetsModule
 * @package app\modules\targets
 */
class TargetsModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Целеполагание';
	}

}
