<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\models\core\core_module\CoreModule;
use app\modules\privileges\models\DynamicUserRights;
use app\modules\privileges\models\UserRightInterface;
use app\modules\privileges\widgets\navigation_menu\UserRightNavigationMenuWidget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use kartik\grid\GridView;

$this->title = 'Правила доступа';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Привилегии');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['правило', 'правила', 'правил']).")":" (нет правил)"),
		'after' => false,
		'footer' => false
	],
	'summary' => Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success summary-content']),
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
			'value' => static function(UserRightInterface $model) {
				return UserRightNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => UserRightNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'name',
			'value' => static function(DynamicUserRights $model) {
				return Html::a($model->name, ['update', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'description'
		]
	]
]) ?>