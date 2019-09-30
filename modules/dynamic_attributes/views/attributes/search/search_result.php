<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var DynamicAttributesSearchCollection $searchCollection //условия поиска для отображения резуьтата у пользователя
 */

use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchCollection;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View; ?>


<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)"),
		'showOnEmpty' => true,
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true
	],
	'columns' => [
		[
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(Users $model) {
				return UserNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => UserNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'value' => static function(Users $model) {
				return Html::img($model->avatar, ['class' => 'img-circle img-xs']);
			},
			'label' => 'Аватар',
			'format' => 'raw',
			'contentOptions' => ['class' => 'text-center'],
			'options' => [
				'style' => 'width: 40px;'
			]
		],
		[
			'attribute' => 'username',
			'value' => static function(Users $model) {
				return Users::a($model->username, ['users/profile', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'label' => 'Должность',
			'attribute' => 'positions',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->relRefUserPositions,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositions::colorStyleOptions(),
					'linkScheme' => [Url::current(['UsersSearch[positions]' => $model->position])]
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'label' => 'Атрибут',
			'value' => static function(Users $model) use ($searchCollection) {
				$result = [];
				foreach ($searchCollection->searchItems as $searchItem) {
					if (null !==$searchItem->attribute ) {
						$result[] = UserAttributeWidget::widget([
							'user_id' => $model->id,
							'attribute_id' => $searchItem->attribute
						]);
					}

				}
				return BadgeWidget::widget([
					'models' => $result,
					'useBadges' => false
				]);
			},
			'format' => 'raw'
		]

	]

]) ?>