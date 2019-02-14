<?php
declare(strict_types = 1);

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\web\View;
use app\modules\references\models\Reference;

/**
 * @var View $this ;
 * @var ArrayDataProvider $dataProvider
 */

$this->title = 'Справочники';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>
			<div class="panel-body">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						[
							'attribute' => 'menuCaption',
							'label' => 'Название справочника',
							'value' => function($referenceModel) {
								/** @var Reference $referenceModel */
								return Html::a($referenceModel->menuCaption, ['index', 'class' => $referenceModel->formName()]);
							},
							'format' => 'raw'
						],
						[
							'attribute' => 'menuIcon',
							'format' => 'raw'
						]
					]
				]); ?>
			</div>
		</div>
	</div>
</div>