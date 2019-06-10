<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $positionConfigurations -- array of configs for current user/group
 * @var integer $currentConfiguration -- current config
 */

use yii\web\View;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use kartik\select2\Select2;

?>
	<div class="position-config-btn-group for-menu">
		<?= Select2::widget([
			'name' => 'positions',
			'data' => $positionConfigurations,
			'value' => $currentConfiguration,
			'options' => [
				'placeholder' => 'Сохранённые позиции'
			],
			'pluginOptions' => [
				'allowClear' => true,
				'multiple' => false
			]
		]) ?>
	</div>
	<div class="position-config-btn-group">
		<?= ButtonDropdown::widget([
			'label' => false,
			'options' => [
				'class' => 'btn-info glyphicon glyphicon-position-config'
			],
			'dropdown' => [
				'items' => [
					[
						'label' => 'Сохранить карту',
						'options' => [
							'onclick' => '$("#config-dialog-modal").modal("show");',
							'class' => 'cursor-pointer'
						],
						'url' => '#'
					],
					[
						'label' => 'Удалить карту',
						'options' => [
							'class' => 'cursor-pointer js-remove-position-config hidden'
						],
						'url' => '#'
					],
					[
						'label' => 'Изменить карту',
						'options' => [
							'class' => 'cursor-pointer js-edit-position-config hidden'
						],
						'url' => '#'
					]
				]
			]
		]) ?>
	</div>
<?php Modal::begin([
	'id' => 'config-dialog-modal',
	'header' => '<h4 class="modal-title">Введите название карты:</h4>',
	'footer' => Html::button('<i class="fa fa-save"></i> Сохранить', ['class' => 'btn btn-success js-save-user-position-config'])
]); ?>
	<div class='form-group'>
		<?= Html::textInput('position-configName', null, ['class' => 'form-control', 'maxlength' => 50, 'id' => 'position-configName']) ?>
	</div>
<?php Modal::end(); ?>

<?php Modal::begin([
	'id' => 'edit_position-configs_modal',
	'header' => '<h4 class="modal-title">Ввведите название карты:</h4>',
	'footer' => Html::button('<i class="fa fa-refresh"></i> Обновить', ['class' => 'btn btn-success js-edit-user-position-config'])
]); ?>
	<div class="form-group">
		<?= Html::textInput('position-configName', null, ['class' => 'form-control', 'maxlength' => 50, 'id' => 'position-configName']) ?>
	</div>
<?php Modal::end(); ?>