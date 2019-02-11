<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 */

use app\helpers\Icons;
use app\models\users\Users;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

?>

<?= ButtonDropdown::widget([
	'label' => Icons::menu(),
	'encodeLabel' => false,
	'dropdown' => [
		'options' => [
			'class' => 'pull-right'
		],
		'encodeLabels' => false,
		'items' => [
			[
				'label' => 'Группы пользователя',
				'url' => ['groups', 'id' => $model->id]
			],
			[
				'label' => 'Атрибуты пользователя',
				'url' => ['attributes', 'id' => $model->id]],
			[
				'label' => 'Новый пользователь',
				'url' => 'create'
			]
		]
	]
]) ?>

