<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var DynamicAttributes $attribute
 */

use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\users\models\Users;
use app\modules\dynamic_attributes\widgets\types_select\AttributeTypesSelectWidget;
use app\modules\users\UsersModule;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = "Изменение атрибута {$attribute->name} ";

$this->params['breadcrumbs'][] = UsersModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = DynamicAttributesModule::breadcrumbItem("Атрибуты пользователя {$user->username}", ['attributes/user', 'user_id' => $user->id]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
	<?php $form = ActiveForm::begin(); ?>
	<div class="panel-heading">
		<div class="panel-control col-md-4">
			<div class="pull-right" style="padding-top: 8px">
				<?= AttributeTypesSelectWidget::widget([
					'userId' => $user->id,
					'attributeId' => $attribute->id
				]) ?>
			</div>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<?= GridView::widget([
					'dataProvider' => new ArrayDataProvider([
						'allModels' => $attribute->getUserProperties($user->id)
					]),
					'showFooter' => false,
					'showPageSummary' => false,
					'summary' => '',
					'panel' => false,
					'toolbar' => false,
					'export' => false,
					'resizableColumns' => true,
					'responsive' => true,
					'columns' => [
						'name',
						[
							'format' => 'raw',
							'attribute' => 'value',
							'label' => 'Значение',
							'value' => static function($model) use ($form) {
								/** @var DynamicAttributeProperty $model */
								return $model->editField($form);
							}
						]
					]
				]) ?>


			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($attribute->isNewRecord?'Сохранить':'Изменить', ['class' => $attribute->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
			<?php if ($attribute->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
			<?php endif ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>




