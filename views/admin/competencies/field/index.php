<?php
declare(strict_types = 1);

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use kartik\grid\ActionColumn;

/**
 * @var View $this
 * @var Competencies $competency
 */

?>


<?= GridView::widget([
	'dataProvider' => new ArrayDataProvider([
		'allModels' => $competency->structure
	]),
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => false,
		'footer' => false
	],
	'toolbar' => [
		[
			'content' => Html::a('Добавить поле', ['field', 'competency_id' => $competency->id], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'attribute' => 'name',
			'label' => 'Название'
		],
		[
			'attribute' => 'type',
			'label' => 'Тип'
		],
		[
			'attribute' => 'required',
			'label' => 'Обязательное поле',
			'format' => 'boolean'
		],
		[
			'class' => ActionColumn::class,
			'template' => '{update} {delete}',
			'buttons' => [
				'update' => function($url, $model) {
					/** @var CompetencyField $model */
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['competencies/field', 'id' => $model->id]);
				}
			]
		]
	]

]); ?>