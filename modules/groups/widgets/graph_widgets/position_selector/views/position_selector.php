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
	],
	'addon' => [
		'prepend' => [
			'content' => ButtonDropdown::widget([
				'label' => false,
				'options' => [
					'class' => 'btn-info fa fa-menu'
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
						]
					]
				]
			]),
			'asButton' => true
		]
	]
]) ?>


<?php Modal::begin([
	'id' => 'config-dialog-modal',
	'header' => '<h4 class="modal-title">Введите название карты:</h4>',
	'footer' => Html::button('<i class="fa fa-save"></i> Сохранить', ['class' => 'btn btn-success js-save-position-config'])
]); ?>
	<div class='form-group'>
		<?= Html::textInput('position-configName', null, ['class' => 'form-control', 'maxlength' => 50, 'id' => 'position-configName']) ?>
	</div>
<?php Modal::end(); ?>