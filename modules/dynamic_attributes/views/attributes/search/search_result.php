<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var DynamicAttributesSearchCollection $searchCollection //условия поиска для отображения результата у пользователя
 */

use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;
use app\models\core\IconsHelper;
use app\components\pozitronik\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchCollection;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchItem;
use app\modules\dynamic_attributes\widgets\dynamic_attribute\DynamicAttributeWidget;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\components\pozitronik\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
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
					'mode' => BaseNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
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
			'label' => 'Результат',
			'value' => static function(Users $model) use ($searchCollection) {
				$result = [];
				$items = [];
				ArrayHelper::getColumn($searchCollection->searchItems, static function(DynamicAttributesSearchItem $element) use (&$items) {
					if (ArrayHelper::keyExists($element->attribute, $items)) {
						$items[$element->property][] = $element->property;
					} else $items[$element->attribute] = (null === $element->property)?null:[$element->property];
				});
				foreach ($items as $attribute_id => $property_id) {
					if (!empty($attribute_id)) {
						$result[] = DynamicAttributeWidget::widget([
							'user_id' => $model->id,
							'attribute_id' => $attribute_id,
							'property_id' => $property_id
						]);
					}

				}
				return BadgeWidget::widget([
					'models' => $result,
					'useBadges' => false,
					'itemsSeparator' => false
				]);
			},
			'format' => 'raw'
		]

	]

]) ?>