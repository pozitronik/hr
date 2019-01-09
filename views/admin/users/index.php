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
use app\models\users\UsersSearch;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

$this->title = 'Люди';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-xs-12">
		<?= /** @noinspection MissedFieldInspection */
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'panel' => [
				'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)")
			],
			'summary' => Html::a('Новый пользователь', 'create', ['class' => 'btn btn-success summary-content']),
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
					'template' => '{update} {delete}',
					'buttons' => [
						'update' => function($url, $model) {
							/** @var UsersSearch $model */
							return Html::tag('li', Html::a(Icons::update().'Изменение', ['update', 'id' => $model->id]));
						},
						'delete' => function($url, $model) {
							/** @var UsersSearch $model */
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
					'value' => function($model) {
						/** @var UsersSearch $model */
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
					'value' => function($model) {
						/** @var UsersSearch $model */
						return Html::a($model->username, ['admin/users/update', 'id' => $model->id]);
					},
					'format' => 'raw'
				],
				[
					'attribute' => 'groupName',
					'label' => 'Группы',
					'value' => function($model) {
						/** @var UsersSearch $model */
						return BadgeWidget::widget([
							'data' => $model->relGroups,
							'useBadges' => false,
							'attribute' => 'name',
							'linkScheme' => ['admin/groups/update', 'id' => 'id']
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
					'value' => function($model) {
						/** @var UsersSearch $model */
						$options = ArrayHelper::map(RefUserRoles::find()->active()->all(), 'id', 'color');
						array_walk($options, function(&$value, $key) {
							if (!empty($value)) {
								$value = [
									'style' => "background: $value;"
								];
							}
						});
						return BadgeWidget::widget([
							'data' => $model->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
							'useBadges' => true,
							'attribute' => 'name',
							'unbadgedCount' => 6,
							"itemsSeparator" => false,
							"optionsMap" => $options
						]);
					},
					'format' => 'raw'
				],
				'email:email'
			]
		]); ?>
	</div>
</div>