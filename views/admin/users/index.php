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
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;
$this->title = 'Люди';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'panel' => [
				'heading' => $this->title
			],
			'toolbar' => [
				[
					'content' => Html::a('Новый', 'create', ['class' => 'btn btn-success'])
				]
			],
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
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
					'template' => '{update} {delete}'
				]
			]
		]); ?>
	</div>
</div>