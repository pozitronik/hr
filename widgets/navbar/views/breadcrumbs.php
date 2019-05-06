<?php

/* @var View $this */

declare(strict_types = 1);

use pozitronik\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\Breadcrumbs;
?>

<?= Breadcrumbs::widget([
	'homeLink' => [
		'label' => 'Домой',
		'url' => Yii::$app->homeUrl
	],
	'links' => ArrayHelper::getValue($this->params, 'breadcrumbs', [])
]) ?>