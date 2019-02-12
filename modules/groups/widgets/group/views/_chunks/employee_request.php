<?php
declare(strict_types = 1);

/**
 * Запрос нового сотрудника (потом уйдёт в свой виджет)
 * @var View $this
 */

use app\models\prototypes\PrototypeEmployeeRequest;
use yii\data\ArrayDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;

$request = new PrototypeEmployeeRequest();
$dataProvider = new ArrayDataProvider(['allModels' => [
	[
		'description' => 'PHP-разработчик',
		'status' => 'На согласовании',
		'who' => 'Твой начальник'
	],
	[
		'description' => 'Телепат',
		'status' => 'Отказано',
		'who' => 'Мистер Бестер'
	]
]]);
?>

<div class="panel">
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($request, 'requestText')->textarea(['placeholder' => 'Нужен работник работать работу']); ?>
		<?= Html::submitButton('Отправить на согласование', ['class' => 'btn btn-primary pull-right']); ?>
		<?php ActiveForm::end(); ?>
	</div>
</div>


<div class="panel">
	<div class="panel-body">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				[
					'attribute' => 'description',
					'label' => 'Кто нужен'
				],
				[
					'attribute' => 'status',
					'label' => 'Статус'
				],
				[
					'attribute' => 'who',
					'label' => 'Текущий согласующий'
				]
			]
		]); ?>
	</div>
</div>
