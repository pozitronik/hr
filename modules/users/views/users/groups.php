<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\modules\groups\models\Groups;
use app\modules\users\widgets\navigation_menu\NavigationMenuWidget;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\users\models\Users;
use app\widgets\roles_select\RolesSelectWidget;
use kartik\grid\ActionColumn;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;

/**
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */
$this->title = "Группы пользователя {$model->username}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/users/users']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= NavigationMenuWidget::widget([
				'model' => $model
			]); ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<?= GridView::widget([
					'dataProvider' => $provider,
					'showFooter' => false,
					'showPageSummary' => false,
					'summary' => '',
					'panel' => [
						'type' => GridView::TYPE_DEFAULT,
						'after' => false,
						'before' => GroupSelectWidget::widget([
							'model' => $model,
							'attribute' => 'relGroups',
							'notData' => $model->relGroups,
							'multiple' => true,
							'mode' => GroupSelectWidget::MODE_FORM
						]),
						'heading' => false,
						'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false
					],
					'toolbar' => false,
					'export' => false,
					'resizableColumns' => true,
					'responsive' => true,
					'columns' => [
						[
							'header' => Icons::menu(),
							'dropdown' => true,
							'dropdownButton' => [
								'label' => Icons::menu(),
								'caret' => ''
							],
							'class' => ActionColumn::class,
							'template' => '{tree}{bunch}',
							'buttons' => [
								'tree' => function($url, $model) {
									/** @var Groups $model */
									return Html::tag('li', Html::a(Icons::network().'Граф структуры', ['/groups/groups/tree', 'id' => $model->id]));
								},
								'bunch' => function($url, $model) {
									/** @var Groups $model */
									return Html::tag('li', Html::a(Icons::users_edit().'Редактирование пользователей', ['/users/bunch/index', 'group_id' => $model->id]));
								}
							]
						],
						[
							'format' => 'raw',
							'attribute' => 'name',
							'value' => function($group) {
								/** @var Groups $group */
								return Html::a($group->name, Url::to(['/groups/groups/profile', 'id' => $group->id]));
							}
						],
						[
							'label' => 'Роли в группе',
							'value' => function($group) use ($model) {
								return RolesSelectWidget::widget([
									'userId' => $model->id,
									'groupId' => $group->id
								]);
							},
							'format' => 'raw'
						],
						[
							'class' => CheckboxColumn::class,
							'headerOptions' => ['class' => 'kartik-sheet-style'],
							'header' => Icons::trash(),
							'name' => $model->formName().'[dropGroups]'
						]
					]

				]); ?>
			</div>
		</div>
	</div>
</div>