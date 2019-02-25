<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes;

use app\models\core\core_module\CoreModule;

/** @noinspection EmptyClassInspection */

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
