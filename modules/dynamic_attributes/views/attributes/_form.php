<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributes $model
 */

use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\View;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?php if (!$model->isNewRecord): ?>
				<?= Html::a('Новый', 'create', ['class' => 'btn btn-success']) ?>
			<?php endif; ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'name') ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'category')->widget(Select2::class, [
					'data' => DynamicAttributes::CATEGORIES
				]) ?>
			</div>
		</div>
		<div class="row">
			<?php if ($model->isNewRecord): ?>
				Редактирование свойств будет доступно после сохранения
			<?php else: ?>
				<?= $this->render('property/index.php', [
					'attribute' => $model,
					'provider' => new ArrayDataProvider(['allModels' => $model->properties])
				]) ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
			<?php if ($model->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
			<?php endif ?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
