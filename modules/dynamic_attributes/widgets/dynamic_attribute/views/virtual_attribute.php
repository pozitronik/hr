<?php
declare(strict_types = 1);

/**
 * Отображение виртуального атрибута - агрегации, не связанной с пользователем напрямую, и не хранящейся в БД.
 * @var View $this
 * @var DynamicAttributes $dynamicAttribute
 * @var DynamicAttributeProperty[] $propertiesCollection
 * @var string $mdClass
 */

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\web\View;

?>

<div class="panel panel-attribute">
	<div class="panel-heading">
		<div class="panel-title">
			<?= $dynamicAttribute->name ?>
		</div>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php foreach ($propertiesCollection as $userProperty): ?>
				<div class="<?= $mdClass ?>">
					<?= $userProperty->viewField([//Каждое свойство атрибута может само определять, каким виджетом его выводить
						'attribute' => 'value',
						'readOnly' => true,
						'showEmpty' => true
					]) ?>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

