<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var bool $showUserSelector Отображать виджет выбора пользователя
 * @var bool $showRolesSelector Отображать колонку выбиралки роли для пользователя (отключаем в некоторых случаях для ускорения)
 * @var bool $showDropColumn Отображать колонку удаления пользюков
 *
 * @var bool|string $heading
 */

use app\helpers\IconsHelper;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\modules\users\widgets\roles_select\RolesSelectWidget;
use app\modules\users\widgets\user_select\UserSelectWidget;
use kartik\grid\CheckboxColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'after' => false,
		'heading' => $heading,
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => $showUserSelector?UserSelectWidget::widget([
			'model' => $model,
			'attribute' => 'relUsers',
			'exclude' => $model->relUsers,
			'multiple' => true,
		]):false
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
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
				return Users::a($user->username, ['/users/profile', 'id' => $user->id]);
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
			'header' => IconsHelper::trash(),
			'name' => $model->formName().'[dropUsers]',
			'visible' => $showDropColumn
		]
	]
]) ?>
