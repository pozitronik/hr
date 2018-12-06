<?php
declare(strict_types = 1);

use app\models\users\Users;
use app\models\groups\Groups;
use app\widgets\roles_select\RolesSelectWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use app\widgets\user_select\UserSelectWidget;

/**
 * @var View $this
 * @var Groups $model
 * @var boolean $selectorInPanel Отображать виджет выбора группы в панели
 * @var boolean $showRolesSelector Отображать колонку выбиралки роли для пользователя (отключаем в некоторых случаях для ускорения)
 * @var boolean $showDropColumn Отображать колонку удаления пользюков
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

$provider = new ActiveDataProvider([
	'query' => $model->getRelUsers()->active()
])
?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => $heading,
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
				'before' => $selectorInPanel?UserSelectWidget::widget([
					'model' => $model,
					'attribute' => 'relUsers',
					'notData' => $model->relUsers,
					'multiple' => true
				]):false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'class' => CheckboxColumn::class,
					'width' => '36px',
					'headerOptions' => ['class' => 'kartik-sheet-style'],
					'header' => 'Удалить',
					'name' => $model->formName().'[dropUsers]',
					'visible' => $showDropColumn
				],
				[
					'format' => 'raw',
					'attribute' => 'username',
					'value' => function($user) {
						/** @var Users $user */
						return Html::a($user->username, ['admin/users/update', 'id' => $user->id]);
					}
				],
				[
					'label' => 'Роли в группе',
					'value' => function($user) use ($model) {
						/** @var Groups $model */
						return RolesSelectWidget::widget([
							'groupId' => $model->id,
							'userId' => $user->id
						]);
					},
					'format' => 'raw',
					'visible' => $showRolesSelector
				]
			]
		]); ?>
	</div>
</div>