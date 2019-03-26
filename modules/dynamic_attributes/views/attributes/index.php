<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\users\models\UsersSearch;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Атрибуты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['атрибут', 'атрибута', 'атрибутов']).")":" (нет атрибутов)")
	],
	'summary' => ButtonGroup::widget([
		'options' => [
			'class' => 'summary-content'
		],
		'buttons' => [
			Html::a('Новый атрибут', 'create', ['class' => 'btn btn-success']),
			Html::a('Поиск', 'search', ['class' => 'btn btn-info'])
		]
	]),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'filter' => false,
			'header' => Icons::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(DynamicAttributes $model) {
				return AttributeNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => AttributeNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'id',
			'options' => [
				'style' => 'width:36px'

			]
		],
		[
			'attribute' => 'name',
			'value' => static function(DynamicAttributes $model) {
				return Html::a($model->name, ['update', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		'categoryName',
		[
			'attribute' => 'usersCount',
			'header' => Icons::users(),
			'headerOptions' => ['class' => 'text-center']
		]
	]

]) ?>