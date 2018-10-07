<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Controller[] $controllers
 **/

use yii\helpers\Html;
use yii\web\Controller;

foreach ($controllers as $controller) {
	echo Html::a("Открыть","{$controller->route}/{$controller->defaultAction}");
}