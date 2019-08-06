<?php
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */

use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\models\relations\RelUsersAttributes;
use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\dynamic_attributes\widgets\attribute_select\AttributeSelectWidget;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use kartik\grid\DataColumn;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

?>

<?= GridView::widget([
	'containerOptions' => [
		'style' => 'overflow-x:inherit'//убираем скроллбар нахер
	],
	'dataProvider' => $provider,
	'showFooter' => false,
	'showPageSummary' => false,
	'summary' => Html::a('Редактор', DynamicAttributesModule::to(['user', 'user_id' => $model->id]), ['class' => 'btn btn-success summary-content']),
	'summaryOptions' => [
		'colspan' => 2
	],
	'panel' => [
		'heading' => 'Атрибуты'.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['атрибут', 'атрибута', 'атрибутов']).")":" (нет атрибутов)")
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => false,
	'responsive' => true,
	'filterPosition' => GridView::FILTER_POS_BODY,
	'columns' => [
		[
			'class' => DataColumn::class,
			'headerOptions' => [/*Фактический хак: таким образом объединяем ячейки заголовка и фильтра без необходимости патчить код фреймворка*/
				'rowspan' => 2,
				'style' => 'width: 50%'
			],
			'contentOptions' => [
				'colspan' => 2,
				'style' => 'padding:0'
			],
			'attribute' => 'type',
			'label' => 'Сортировать по типу отношения атрибута',
			'value' => static function(RelUsersAttributes $attribute) use ($model) {
				return UserAttributeWidget::widget([
					'user_id' => $model->id,
					'attribute_id' => $attribute->attribute_id
				]);
			},
			'format' => 'raw'
		]
	]

]) ?>