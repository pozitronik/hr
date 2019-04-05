<?php
declare(strict_types = 1);

namespace app\modules\history;

use app\models\core\core_module\CoreModule;

/**
 * Class HistoryModule
 * @package app\modules\history
 */
class HistoryModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'История изменений';
	}
}
