<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributes $dynamicAttribute
 * @var DynamicAttributeProperty[] $userProperties
 * @var string $mdClass
 * @var int $user_id
 * @var bool $read_only
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\references\refs\RefAttributesTypes;
use app\models\relations\RelUsersAttributesTypes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\widgets\badge\BadgeWidget;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

$items = [
	[
		'label' => Icons::attributes().'Открыть для изменения',
		'url' => ['/attributes/user/edit', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
	],
	[
		'label' => Icons::clear().'Сбросить все значения',
		'url' => ['/attributes/user/clear', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
	]
];

if ($dynamicAttribute->hasIntegerProperties) $items[] = [
	'label' => Icons::chart().'Диаграмма',
	'url' => ['/attributes/user/graph', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
];

?>

<div class="panel panel-attribute">
	<div class="panel-heading">
		<?php if ($read_only): ?>
			<div class="panel-control">
				<?= ButtonDropdown::widget([
					'label' => Icons::menu(),
					'encodeLabel' => false,
					'options' => [
						'class' => 'attribute-dropdown'
					],
					'dropdown' => [
						'options' => [
							'class' => 'pull-right'
						],
						'encodeLabels' => false,
						'items' => $items
					]
				]) ?>
			</div>
		<?php endif; ?>
		<div class="panel-title">
			<?= $dynamicAttribute->name ?>

			<?= BadgeWidget::widget([
				'data' => RelUsersAttributesTypes::getRefAttributesTypes($user_id, $dynamicAttribute->id),
				'attribute' => 'name',
				'unbadgedCount' => 3,
				'itemsSeparator' => false,
				'optionsMap' => function() {
					$options = ArrayHelper::map(RefAttributesTypes::find()->active()->all(), 'id', 'color');
					array_walk($options, function(&$value, $key) {
						if (!empty($value)) {
							$value = [
								'style' => "background: $value;"
							];
						}
					});
					return $options;
				}
			]); ?>
		</div>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php foreach ($userProperties as $userProperty): ?>
				<div class="<?= $mdClass ?>">
					<?= $userProperty->viewField([//Каждое свойство атрибута может само определять, каким виджетом его выводить
						'attribute' => 'value',
						'readOnly' => $read_only,
						'showEmpty' => true
					]); ?>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

