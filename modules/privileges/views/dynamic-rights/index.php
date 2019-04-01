<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\core\core_module\CoreModule;
use app\modules\privileges\models\DynamicUserRights;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Правила доступа';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Привилегии');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title
	],
	'toolbar' => [
		[
			'content' => Html::a('Новая привилегия', ['create'], ['class' => 'btn btn-success'])
		],
		[
			'content' => Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
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