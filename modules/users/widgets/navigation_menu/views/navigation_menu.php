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
				'label' => Icons::user().'Профиль пользователя',
				'url' => ['/users/users/profile', 'id' => $model->id]
			],
			[
				'label' => Icons::group().'Группы пользователя',
				'url' => ['/users/users/groups', 'id' => $model->id]
			],
			[
				'label' => Icons::attributes().'Атрибуты пользователя',
				'url' => ['/users/users/attributes', 'id' => $model->id]
			],
			[
				'label' => Icons::user_add().'Новый пользователь',
				'url' => '/users/users/create'
			]
		]
	]
]) ?>

