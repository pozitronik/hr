<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Model $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var string|array $updateAttributeAction
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\references\refs\RefAttributesTypes;
use app\models\relations\RelUsersAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\Users;
use app\modules\dynamic_attributes\widgets\attribute_select\AttributeSelectWidget;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use app\modules\users\widgets\navigation_menu\NavigationMenuWidget;
use kartik\select2\Select2;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\GridView;

$this->title = "Атрибуты пользователя {$user->username}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/users/users']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= NavigationMenuWidget::widget([
				'model' => $user
			]); ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">

			<div class="panel panel-attribute">
				<div class="panel-body">
					<?= GridView::widget([
						'containerOptions' => [
							'style' => 'overflow-x:inherit'//убираем скроллбар нахер
						],
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						'showFooter' => false,
						'showPageSummary' => false,
						'summaryOptions' => [
							'colspan' => 2
						],
						'panel' => [
							'type' => GridView::TYPE_DEFAULT,
							'after' => false,
							'before' => AttributeSelectWidget::widget([
								'model' => $user,
								'attribute' => 'relDynamicAttributes',
								'mode' => AttributeSelectWidget::MODE_FORM,
								'multiple' => true,
								'formAction' => $updateAttributeAction
							]),
							'heading' => false,
							'footer' => false
						],
						'toolbar' => false,
						'export' => false,
						'resizableColumns' => false,
						'responsive' => true,
						'filterPosition' => GridView::FILTER_POS_BODY,
						'columns' => [
							[
								'headerOptions' => [/*Фактический хак: таким образом объединяем ячейки заголовка и фильтра без необходимости патчить код фреймворка*/
									'rowspan' => 2,
									'style' => 'width: 50%',
								],
								'contentOptions' => [
									'colspan' => 2,
									'style' => 'padding:0'
								],
								'filterOptions' => [
									'style' => 'padding:0px 10px 0px 0px; vertical-align: middle; width: 50%;',
								],
								'attribute' => 'type',
								'label' => 'Сортировать по типу отношения атрибута',
								'filterType' => GridView::FILTER_SELECT2,
								'filter' => RefAttributesTypes::mapData(),
								'filterInputOptions' => ['placeholder' => 'Фильтр по типу'],
								'filterWidgetOptions' => [
									'size' => Select2::SMALL,
									'pluginOptions' => [
										'allowClear' => true, 'multiple' => true
									]
								],
								'value' => function($model) use ($user) {
									/** @var RelUsersAttributes $model */
									return UserAttributeWidget::widget([
										'user_id' => $user->id,
										'attribute_id' => $model->attribute_id
									]);
								},
								'format' => 'raw'
							],
						]

					]); ?>

				</div>

			</div>
		</div>
	</div>
</div>


