<?php
declare(strict_types = 1);

namespace app\components\pozitronik\references;

use app\components\pozitronik\core\models\core_module\CoreModule;
use Yii;

/**
 * Class ReferencesModule
 * @package app\modules\references
 */
class ReferencesModule extends CoreModule {

	/**
	 * {@inheritDoc}
	 */
	public function getControllerPath() {
		return Yii::getAlias('@app/components/pozitronik/references/controllers');
	}
}
