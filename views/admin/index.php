<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Controller[] $controllers
 **/

use app\widgets\admin_control\ControlerWidget;
use yii\web\Controller;
use yii\web\View;

foreach ($controllers as $controller) {
	echo ControlerWidget::widget([
		'model' => $controller
	]);
}