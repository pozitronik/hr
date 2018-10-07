<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Controller[] $controllers
 **/

use app\widgets\admin_control\Admin_controlWidget;
use yii\web\Controller;
use yii\web\View;

foreach ($controllers as $controller) {
	echo Admin_controlWidget::widget([
		'model' => $controller
	]);
}