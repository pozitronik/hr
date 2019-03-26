<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\models\core\core_module\CoreModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\users\models\Users;
use app\modules\references\widgets\roles_select\RolesSelectWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;

/**
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */
$this->title = "Группы пользователя {$model->username}";
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= UserNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
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
							'mode' => GroupSelectWidget::MODE_FORM,
							'dataMode' => GroupSelectWidget::DATA_MODE_AJAX
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
							'filter' => false,
							'header' => Icons::menu(),
//							'mergeHeader' => true,
							'headerOptions' => [
								'class' => 'skip-export kv-align-center kv-align-middle'
							],
							'contentOptions' => [
								'style' => 'width:50px',
								'class' => 'skip-export kv-align-center kv-align-middle'
							],
							'value' => static function(Groups $model) {
								return GroupNavigationMenuWidget::widget([
									'model' => $model,
									'mode' => GroupNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
								]);
							},
							'format' => 'raw'
						],
						[
							'format' => 'raw',
							'attribute' => 'name',
							'value' => static function($group) {
								/** @var Groups $group */
								return Html::a($group->name, ['profile', 'id' => $group->id]);
							}
						],
						[
							'label' => 'Роли в группе',
							'value' => static function($group) use ($model) {
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

				]) ?>
			</div>
		</div>
	</div>
</div>