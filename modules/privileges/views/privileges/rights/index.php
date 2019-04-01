<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\helpers\Utils;

/**
 * @var View $this
 * @var ArrayDataProvider $provider
 * @var string $heading
 * @var Privileges $model
 */

use app\modules\privileges\models\Privileges;
use app\modules\references\widgets\user_right_select\UserRightSelectWidget;
use yii\data\ArrayDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;

?>
<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['право', 'права', 'прав']).")":" (нет прав)"),
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => "<div class='col-md-6'>".UserRightSelectWidget::widget([
				'model' => $model,
				'attribute' => 'userRightsNames',//Выбиралка передаёт имена классов, метод модели подхватывает именно этот параметр
				'notData' => $model->isNewRecord?[]:$model->userRights,
				'multiple' => true,
				'mode' => UserRightSelectWidget::MODE_MODELS
			])."</div><div class='col-md-6'>".UserRightSelectWidget::widget([
				'model' => $model,
				'attribute' => 'userDynamicRightsIds',//Выбиралка передаёт имена классов, метод модели подхватывает именно этот параметр
				'notData' => $model->isNewRecord?[]:$model->userRights,
				'multiple' => true,
				'mode' => UserRightSelectWidget::MODE_DYNAMIC
			])."</div>"
	],
	'toolbar' => false,
	'export' => false,
	'summary' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'showFooter' => true,
	'footerRowOptions' => [],
	'columns' => [
		[
			'attribute' => 'name',
			'format' => 'raw'
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
			'header' => Icons::trash(),
			'name' => $model->formName().'[dropUserRights]'
		]
	]

]) ?>