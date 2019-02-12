<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\helpers\Utils;
use app\models\references\refs\RefUserRoles;
use app\models\user_rights\Privileges;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

if (null !== $searchModel) {//Учитываем вызов из поиска по атрибутам, пока используется одна вьюха на всё.
	$this->title = 'Люди';
	$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
	$this->params['breadcrumbs'][] = $this->title;
}

?>

<?= /** @noinspection MissedFieldInspection */
GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)")
	],
	'summary' => null !== $searchModel?Html::a('Новый пользователь', 'create', ['class' => 'btn btn-success summary-content']):null,
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'class' => ActionColumn::class,
			'header' => Icons::menu(),
			'dropdown' => true,
			'dropdownButton' => [
				'label' => Icons::menu(),
				'caret' => ''
			],
			'template' => '{profile} {groups} {attributes} {delete}',
			'buttons' => [

				'profile' => function(string $url, Users $model) {
					return Html::tag('li', Html::a(Icons::user().'Профиль', ['/users/users/profile', 'id' => $model->id]));
				},
				'groups' => function(string $url, Users $model) {
					return Html::tag('li', Html::a(Icons::group().'Группы', ['/users/users/groups', 'id' => $model->id]));
				},
				'attributes' => function(string $url, Users $model) {
					return Html::tag('li', Html::a(Icons::attributes().'Атрибуты', ['/users/users/attributes', 'id' => $model->id]));
				},
				'delete' => function(string $url, Users $model) {
					return Html::tag('li', Html::a(Icons::delete().'Удаление', ['delete', 'id' => $model->id], [
						'title' => 'Удалить запись',
						'data' => [
							'confirm' => $model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
							'method' => 'post'
						]
					]));
				}
			]
		],
		[
			'attribute' => 'id',
			'options' => [
				'style' => 'width:36px'
			]
		],
		[
			'value' => function(Users $model) {
				return Html::img($model->avatar, ['class' => 'img-circle img-xs']);
			},
			'label' => 'Аватар',
			'format' => 'raw',
			'contentOptions' => ['class' => 'text-center'],
			'options' => [
				'style' => 'width: 40px;'
			]
		],
		[
			'attribute' => 'username',
			'value' => function(Users $model) {
				return Html::a($model->username, ['/users/users/profile', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'groupName',
			'label' => 'Группы',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->relGroups,
					'useBadges' => false,
					'attribute' => 'name',
					'linkScheme' => ['/groups/groups/profile', 'id' => 'id']
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'roles',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => RefUserRoles::mapData(),
			'filterInputOptions' => ['placeholder' => 'Выберите роль'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],

			'label' => 'Роли',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false,
					"optionsMap" => function() {
						$options = ArrayHelper::map(RefUserRoles::find()->active()->all(), 'id', 'color');
						array_walk($options, function(&$value, $key) {
							if (!empty($value)) {
								$value = [
									'style' => "background: $value;"
								];
							}
						});
						return $options;
					}
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'privileges',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => ArrayHelper::map(Privileges::find()->active()->all(), 'id', 'name'),
			'filterInputOptions' => ['placeholder' => 'Выберите привилегии'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],

			'label' => 'Привилегии',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->getRelPrivileges()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false
				]);
			},
			'format' => 'raw'
		]

	]
]); ?>