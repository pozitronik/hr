<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 */

use app\modules\privileges\models\ActionAccess;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-control">
				<?php if (!$model->isNewRecord): ?>
					<?= Html::a('Новая привилегия', ['create'], ['class' => 'btn btn-success']) ?>
					<?= Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success']) ?>
				<?php endif; ?>
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'name') ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= GridView::widget([
						'dataProvider' => $model->actionsAccessProvider,
						'panel' => [
							'type' => GridView::TYPE_DEFAULT
						],
						'columns' => [
							'moduleId',
							'controllerId',
							'actionName',
							[
								'attribute' => 'state',
								'format' => 'raw',
								'value' => static function(ActionAccess $actionAccess) use ($model) {
									return SwitchInput::widget([
										'value' => $actionAccess->state,
										'name' => "{$model->formName()}[actionsAccessMap][{$actionAccess->id}]",
										'pluginOptions' => [
											'size' => 'mini',
											'onText' => 'ДА',
											'offText' => 'НЕТ',
											'onColor' => 'primary',
											'offColor' => 'gray'
										]
									]);
								}
							]
						]

					]) ?>
				</div>
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