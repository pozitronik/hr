<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\users\Users;
use app\models\users\UsersSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\grid\ActionColumn;

?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
//			'panel' => [
//				'heading' => $this->title
//			],
//			'export' => false,
//			'resizableColumns' => false,
//			'responsive' => true,
			'columns' => [
				'id',
				[
					'value' => function($column) {
						/** @var Users $column */
						return Html::img($column->avatar, ['class' => 'img-circle img-xs']);
					},
					'label' => 'Аватар',
					'format' => 'raw'
				],
				[
					'attribute' => 'username',
					'value' => function($model) {
						/** @var Users $model */
						return $model->username;
//						return UserWidget::widget([
//							'user' => $model,
//							'chat' => false,
//							'update' => false
//						]);
					},
					'format' => 'raw'
				],
				'login',
				'email:email',
				[
					'class' => ActionColumn::class,
					'template' =>'{update} {delete}'
				]
			]
		]); ?>
	</div>
</div>