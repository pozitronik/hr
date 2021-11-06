<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes;

use app\components\pozitronik\core\models\core_module\CoreModule;

/**
 * Class DynamicAttributesModule
 * @package app\modules\dynamic_attributes
 */
class DynamicAttributesModule extends CoreModule {

	/**
	 * {@inheritDoc}
	 */
	public function getName():string {
		return 'Поддержка динамических атрибутов';
	}
}
