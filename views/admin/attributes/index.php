<?php
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\models\users\UsersSearch;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;
use app\models\dynamic_attributes\DynamicAttributes;

$this->title = 'Атрибуты';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-xs-12">
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
					'header' => Icons::menu(),
					'dropdown' => true,
					'dropdownButton' => [
						'label' => Icons::menu(),
						'caret' => ''
					],
					'class' => ActionColumn::class,
					'template' => '{update} {delete}'
				],
				[
					'attribute' => 'id',
					'options' => [
						'style' => 'width:36px'

					]
				],
				'name',
				'categoryName',
				[
					'attribute' => 'usersCount',
					'header' => Icons::users(),
					'headerOptions' => ['class' => 'text-center']
				]
			]

		]); ?>
	</div>
</div>