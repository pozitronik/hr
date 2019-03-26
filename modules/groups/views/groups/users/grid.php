<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var bool $showUserSelector Отображать виджет выбора пользователя
 * @var bool $showRolesSelector тображать колонку выбиралки роли для пользователя (отключаем в некоторых случаях для ускорения)
 * @var bool $showDropColumn Отображать колонку удаления пользюков
 *
 * @var bool|string $heading
 */

use app\helpers\Icons;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\modules\users\widgets\user_select\UserSelectWidget;
use app\modules\references\widgets\roles_select\RolesSelectWidget;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
?>

<?= /** @noinspection MissedFieldInspection */
GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'after' => false,
		'heading' => $heading,
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => $showUserSelector?UserSelectWidget::widget([
			'model' => $model,
			'attribute' => 'relUsers',
			'notData' => $model->relUsers,
			'multiple' => true,
			'mode' => UserSelectWidget::MODE_FORM,
			'dataMode' => UserSelectWidget::DATA_MODE_AJAX
		]):false
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'filter' => false,
			'header' => Icons::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(Users $model) {
				return UserNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => UserNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'format' => 'raw',
			'attribute' => 'username',
			'value' => static function(Users $user) {
				return Html::a($user->username, ['/users/users/profile', 'id' => $user->id]);//todo module navigation
			}
		],
		[
			'label' => 'Роли в группе',
			'value' => static function(Users $user) use ($model) {
				return RolesSelectWidget::widget([
					'groupId' => $model->id,
					'userId' => $user->id
				]);
			},
			'format' => 'raw',
			'visible' => $showRolesSelector
		],
		[
			'class' => CheckboxColumn::class,
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => Icons::trash(),
			'name' => $model->formName().'[dropUsers]',
			'visible' => $showDropColumn
		]
	]
]); ?>
