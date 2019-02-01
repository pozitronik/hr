<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributes $dynamicAttribute
 * @var DynamicAttributeProperty[] $userProperties
 * @var string $mdClass
 */

use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use yii\web\View;

?>

<div class="panel panel-primary panel-attribute">
	<div class="panel-heading">
		<div class="panel-title">Атрибут: <?= $dynamicAttribute->name ?> (<?= $dynamicAttribute->categoryName ?>)</div>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php foreach ($userProperties as $userProperty): ?>
				<div class="<?= $mdClass ?>">
					<?= $userProperty->widget([//Каждое свойство атрибута может само определять, каким виджетом его выводить
						'attribute' => 'value',
						'readOnly' => true,
						'showEmpty' => false
					]); ?>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

