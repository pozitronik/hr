<?php
declare(strict_types = 1);

namespace app\modules\graph;

use app\components\pozitronik\core\models\core_module\CoreModule;

/**
 * Class GraphModule
 * @package app\modules\graph
 */
class GraphModule extends CoreModule {
	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Визуализация структур';
	}
}
