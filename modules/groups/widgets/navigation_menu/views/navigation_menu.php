<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\helpers\Icons;
use app\modules\groups\models\Groups;
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
				'label' => Icons::group().'Профиль группы',
				'url' => ['/groups/groups/profile', 'id' => $model->id]
			],
			[
				'label' => Icons::subgroups().'Иерархия группы',
				'url' => ['/groups/groups/groups', 'id' => $model->id]
			],
			[
				'label' => Icons::network().'Граф иерархии',
				'url' => ['/groups/groups/tree', 'id' => $model->id]
			],
			[
				'label' => Icons::users().'Пользователи в группе',
				'url' => ['/groups/groups/users', 'id' => $model->id]
			],
			[
				'label' => Icons::hierarchy().'Иерархия пользователей',
				'url' => ['/groups/groups/users-hierarchy', 'id' => $model->id]
			],
			[
				'label' => Icons::hierarchy_red().'Иерархия пользователей (с ролями)',
				'url' => ['/groups/groups/users-hierarchy', 'showRolesSelector' => true, 'id' => $model->id]
			],
			[
				'label' => Icons::users_edit().'Редактировать пользователей',
				'url' => ['/users/bunch/index', 'group_id' => $model->id]
			],
			[
				'label' => Icons::users_edit_red().'Редактировать пользователей (с учётом иерархии)',
				'url' => ['/users/bunch/index', 'group_id' => $model->id, 'hierarchy' => true]
			],
			[
				'label' => Icons::add().'Новая группа',
				'url' => ['/groups/groups/create']
			]
		]
	]
]) ?>

