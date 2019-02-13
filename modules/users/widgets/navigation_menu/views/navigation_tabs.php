<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var array $items
 */

use app\helpers\Utils;
use app\modules\users\models\Users;
use yii\helpers\Html;
use yii\web\View;

?>

<ul class="nav nav-tabs">

	<?php foreach ($items as $item): ?>
		<?= Html::tag('li', Html::a($item['label'], $item['url']), ['class' => Utils::isSameUrlPath($item['url'])?'active':'inactive']); ?>
	<?php endforeach; ?>
</ul>


