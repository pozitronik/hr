<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\structure;

use app\models\user\CurrentUser;
use pozitronik\helpers\ArrayHelper;
use Yii;
use yii\base\Widget;

/**
 * Class StructureWidget
 * @package app\modules\groups\widgets\structure
 */
class StructureWidget extends Widget {

	public $id;

	public function init() {
		parent::init();
		StructureWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		StructureWidgetAssets::register($this->getView());
		$positionConfigurations = ['default' => 'default'];
		$groupId = Yii::$app->request->get('id');//временный код, это перелезет в контроллеры
		/** @var array $groupMapConfigurations */
		if (false !== $groupMapConfigurations = ArrayHelper::getValue(CurrentUser::User()->options->nodePositionsConfig, $groupId, false)) {
			foreach ($groupMapConfigurations as $name => $nodes) {
				$positionConfigurations[$name] = $name;
			}
		}

		return $this->render('tree', [
			'id' => $this->id,
			'currentConfiguration' => 'default',
			'positionConfigurations' => $positionConfigurations
		]);

	}
}
