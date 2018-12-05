<?php
declare(strict_types = 1);

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use kartik\grid\ActionColumn;
use app\helpers\ArrayHelper;

/**
 * @var View $this
 * @var Competencies $competency
 */

$provider = new ArrayDataProvider(['allModels' => $competency->structure]);//todo controller
?>


<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => false,
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false
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
			'attribute' => 'id',
			'label' => 'id'
		],
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
				'update' => function($url, $model) use ($competency) {
					/** @var CompetencyField $model */
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['admin/competencies/field', 'competency_id' => $competency->id, 'field_id' => ArrayHelper::getValue($model, 'id')]);
				}
			]
		]
	]

]); ?>