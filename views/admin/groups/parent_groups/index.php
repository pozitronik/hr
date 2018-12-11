<?php
declare(strict_types = 1);

use app\helpers\Utils;
use app\models\groups\Groups;
use app\widgets\group_select\GroupSelectWidget;

/**
 * @var View $this
 * @var Groups $model
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;

$provider = new ActiveDataProvider([
	'query' => $model->getRelParentGroups()->orderBy('name')->active()
]);
?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => $heading,
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
				'before' => GroupSelectWidget::widget([
					'model' => $model,
					'attribute' => 'relParentGroups',
					'notData' => $model->isNewRecord?[]:array_merge($model->relParentGroups, [$model]),
					'multiple' => true
				])
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'showFooter' => true,
//			'footerRowOptions' => ['style' => 'font-weight:bold;text-decoration: underline;'],
			'columns' => [
				[
					'class' => CheckboxColumn::class,
					'width' => '36px',
					'headerOptions' => ['class' => 'kartik-sheet-style'],
					'header' => 'Удалить',
					'name' => $model->formName().'[dropParentGroups]'
				],
				[
					'format' => 'raw',
					'attribute' => 'name',
					'value' => function($group) {
						/** @var Groups $group */
						return Html::a($group->name, Url::to(['admin/groups/update', 'id' => $group->id]));
					}
				],
				[
					'attribute' => 'usersCount',
					'label' => 'Пользователей',
					'footer' => Utils::pageTotal($provider, 'usersCount')
				],
				[
					'attribute' => 'childGroupsCount',
					'label' => 'Подгрупп',
					'footer' => Utils::pageTotal($provider, 'childGroupsCount')
				],
				[
//					'dropdown' => true,
					'class' => ActionColumn::class,
					'template' => '{tree}{bunch}',
					'buttons' => [
						'tree' => function($url, $model) {
							return Html::a('Граф', $url, ['class' => 'btn btn-xs btn-info']);
						},
						'bunch' => function($url, $model) {
							/** @var Groups $model */
							return Html::a('Редактировать пользователей', ['admin/bunch/index', 'group_id' => $model->id], ['class' => 'btn btn-xs btn-info']);
						}
					]
				]
			]

		]); ?>
	</div>
</div>