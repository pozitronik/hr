<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\helpers\Utils;
use app\models\groups\Groups;

/**
 * @var View $this
 * @var ArrayDataProvider $provider
 * @var string $heading
 * @var Privileges $model
 */

use app\models\user_rights\Privileges;
use yii\data\ArrayDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use kartik\grid\ActionColumn;

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['право', 'права', 'прав']).")":" (нет прав)"),
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
//				'before' => UserRightsWidget::widget([
//					'model' => $model,
//					'attribute' => 'relParentGroups',
//					'notData' => $model->isNewRecord?[]:array_merge($model->relParentGroups, [$model]),
//					'multiple' => true
//				])
			],
			'toolbar' => false,
			'export' => false,
			'summary' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'showFooter' => true,
			'footerRowOptions' => [],
			'columns' => [
				'name',
				'description',
				[
					'class' => CheckboxColumn::class,
					'headerOptions' => ['class' => 'kartik-sheet-style'],
					'header' => Icons::trash(),
					'name' => $model->formName().'[dropParentGroups]'
				]
			]

		]); ?>
	</div>
</div>