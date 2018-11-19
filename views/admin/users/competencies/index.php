<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var Users $user
 * @var array $data
 **/

use yii\web\View;
use kartik\grid\GridView;
use app\models\users\Users;
use yii\data\ActiveDataProvider;
use kartik\select2\Select2;

$this->title = 'Компетенции пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = ['label' => 'Профиль пользователя '.$user->username, 'url' => ['/admin/users/update', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => new ActiveDataProvider([
				'query' => $user->getRelCompetencies()->orderBy('name')
			]),
			'showFooter' => false,
			'showPageSummary' => false,
			'summary' => '',
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'before' => Select2::widget([
					'model' => $user,
					'attribute' => 'relCompetencies',
					'name' => 'competency_id',
					'data' => $data,
					'options' => [
						'multiple' => true,
						'placeholder' => 'Добавить компетенцию'
					]
				]),
				'heading' => false,
				'footer' => false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true

		]); ?>
	</div>
</div>