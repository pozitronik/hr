<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicUserRights $model
 */

use app\helpers\Utils;
use app\modules\privileges\models\ActionAccess;
use app\modules\privileges\models\DynamicUserRights;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-control">
				<?php if (!$model->isNewRecord): ?>
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
					<?= $form->field($model, 'description') ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= /** @noinspection MissedFieldInspection */
					GridView::widget([
						'dataProvider' => $model->actionsAccessProvider,
						'panel' => [
							'type' => GridView::TYPE_DEFAULT,
							'heading' => 'Доступ к действиям'.(($model->actionsAccessProvider->totalCount > 0)?" (".Utils::pluralForm($model->actionsAccessProvider->totalCount, ['действие', 'действия', 'действий']).")":" (нет действий)"),
							'after' => false,
							'footer' => false
						],
						'toolbar' => false,
						'export' => false,
						'resizableColumns' => true,
						'responsive' => true,
						'summary' => false,

						'columns' => [
							[
								'attribute' => 'moduleId',
								'value' => 'moduleDescription',
								'format' => 'raw',
								'group' => true
							],
							[
								'attribute' => 'controllerId',
								'value' => 'controllerDescription',
								'format' => 'raw',
								'group' => true
							],
							[
								'attribute' => 'actionName',
								'value' => 'actionDescription',
								'format' => 'raw'
							],
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