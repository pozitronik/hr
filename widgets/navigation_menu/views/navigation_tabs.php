<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $items
 */

use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\Utils;
use yii\helpers\Html;
use yii\web\View;

?>

<ul class="nav nav-tabs">
	<?php foreach ($items as $item): ?>
		<?= Html::tag('li', Html::a($item['label'], $item['url']), ArrayHelper::getValue($item, 'linkOptions', []) + ['class' => Utils::isSameUrlPath($item['url'])?'active':'inactive']) ?>
	<?php endforeach; ?>
</ul>


