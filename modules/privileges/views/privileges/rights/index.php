<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ArrayDataProvider $provider
 * @var string $heading
 * @var Privileges $model
 */

use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;
use app\modules\privileges\models\DynamicUserRights;
use app\modules\privileges\models\Privileges;
use app\modules\privileges\widgets\navigation_menu\UserRightNavigationMenuWidget;
use kartik\grid\DataColumn;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use app\models\core\IconsHelper;
use app\modules\privileges\widgets\user_right_select\UserRightSelectWidget;
use app\components\pozitronik\core\interfaces\access\UserRightInterface;
use app\components\pozitronik\helpers\Utils;

?>
<?=
GridView::widget([
	'dataProvider' => $provider,

	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['правило', 'правила', 'правил']).")":" (нет прав)"),
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => Html::tag('div', UserRightSelectWidget::widget([
				'model' => $model,
				'attribute' => 'userRightsNames',//Выбиралка передаёт имена классов, метод модели подхватывает именно этот параметр
				'notData' => $model->isNewRecord?[]:$model->userRights,
				'multiple' => true,
				'mode' => UserRightSelectWidget::MODE_MODELS
			]), ['class' => 'col-md-6']).Html::tag('div', UserRightSelectWidget::widget([
				'model' => $model,
				'attribute' => 'userDynamicRightsIds',//Выбиралка передаёт имена классов, метод модели подхватывает именно этот параметр
				'notData' => $model->isNewRecord?[]:$model->userRights,
				'multiple' => true,
				'mode' => UserRightSelectWidget::MODE_DYNAMIC
			]), ['class' => 'col-md-6'])
	],
	'toolbar' => false,
	'export' => false,
	'summary' => Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success summary-content']),
	'emptyText' => Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success summary-content']),
	'resizableColumns' => true,
	'responsive' => true,
	'showFooter' => true,
	'footerRowOptions' => [],
	'columns' => [
		[
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(UserRightInterface $model) {
				return UserRightNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => BaseNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'name',
			'format' => 'raw',
			'value' => static function(UserRightInterface $model) {
				if (is_a($model, DynamicUserRights::class)) {
					return Html::a($model->name, ['dynamic-rights/update', 'id' => $model->id]);
				}
				return $model->name;
			}
		],
		[
			'attribute' => 'description',
			'format' => 'raw'
		],
		[
			'attribute' => 'module',
			'format' => 'raw'
		],
		[
			'class' => CheckboxColumn::class,
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => IconsHelper::trash(),
			'name' => $model->formName().'[dropUserRights]'
		]
	]

]) ?>