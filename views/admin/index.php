<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController[] $controllers
 **/

use pozitronik\core\models\core_controller\WigetableController;
use app\widgets\controller\ControllerWidget;
use yii\web\View;

$this->title = 'Управление';
$this->params['breadcrumbs'][] = $this->title;

foreach ($controllers as $controller) {
	if (!$controller->menuDisabled) {
		echo ControllerWidget::widget([
			'model' => $controller
		]);
	}

}