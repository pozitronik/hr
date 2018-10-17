<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController[] $controllers
 **/

use app\models\core\WigetableController;
use app\widgets\controller\ControllerWidget;
use yii\web\View;

foreach ($controllers as $controller) {
	if (!$controller->disabled) {
		echo ControllerWidget::widget([
			'model' => $controller
		]);
	}

}