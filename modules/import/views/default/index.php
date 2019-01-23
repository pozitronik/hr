<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var int $domain
 */

use app\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => 'Загружено '.Utils::pluralForm($dataProvider->totalCount, ['строка', 'строки', 'строк'])
	],
	'toolbar' => [
		[
			'content' => Html::a('Декомпозиция', ['decompose', 'domain' => $domain], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
	]
]); ?>