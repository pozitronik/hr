<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;

/**
 * @var View $this
 * @var Groups $model
 */
$provider = new ActiveDataProvider([
	'query' => $model->getRelChildGroups()->orderBy('name')->active()
]);//todo controller

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => false,
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
				'before' => false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'format' => 'raw',
					'attribute' => 'name',
					'value' => function($model) {
						/** @var Groups $model */
						$gc = $model->getRelChildGroups()->active()->count();
						$uc = $model->getRelUsers()->count();
						return "$gc | $uc";
					}
				]
			]
		]); ?>
	</div>
</div>