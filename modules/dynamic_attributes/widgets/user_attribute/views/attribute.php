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

use app\helpers\Icons;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

$items = [
	[
		'label' => Icons::attributes().'Открыть для изменения',
		'url' => ['attributes/user/edit', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
	],
	[
		'label' => Icons::clear().'Сбросить все значения',
		'url' => ['attributes/user/clear', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
	]
];

if ($dynamicAttribute->hasIntegerProperties) $items[] = [
	'label' => Icons::chart().'Диаграмма',
	'url' => ['attributes/user/graph', 'user_id' => $user_id, 'attribute_id' => $dynamicAttribute->id]
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
		<div class="panel-title">Атрибут: <?= $dynamicAttribute->name ?> (<?= $dynamicAttribute->categoryName ?>)</div>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php foreach ($userProperties as $userProperty): ?>
				<div class="<?= $mdClass ?>">
					<?= $userProperty->viewField([//Каждое свойство атрибута может само определять, каким виджетом его выводить
						'attribute' => 'value',
						'readOnly' => $read_only,
						'showEmpty' => false
					]); ?>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

