<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Controller[] $controllers
 **/

use app\widgets\controller\ControllerWidget;
use yii\web\Controller;
use yii\web\View;

foreach ($controllers as $controller) {
	echo ControllerWidget::widget([
		'model' => $controller
	]);
}