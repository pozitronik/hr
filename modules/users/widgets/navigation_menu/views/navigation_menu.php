<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var array $items
 */

use app\helpers\Icons;
use app\modules\users\models\Users;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

?>

<?= ButtonDropdown::widget([
	'label' => Icons::menu(),
	'encodeLabel' => false,
	'dropdown' => [
		'options' => [
			'class' => 'pull-right'
		],
		'encodeLabels' => false,
		'items' => $items
	]
]) ?>

