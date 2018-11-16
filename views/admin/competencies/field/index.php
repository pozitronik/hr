<?php
declare(strict_types = 1);

use app\models\competencies\Competencies;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/**
 * @var View $this
 * @var Competencies $competency
 */

?>


<?= GridView::widget([
	'dataProvider' => new ArrayDataProvider([
		'allModels' => [
		]
	]),
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => false,
		'footer' => false,
	],
	'toolbar' => [
		[
			'content' => Html::a('Добавить поле', ['field', 'competency' => $competency->id], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		'name',
		'type',
		'required'
	]

]); ?>