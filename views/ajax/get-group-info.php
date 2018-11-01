<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use yii\web\View;
use kartik\detail\DetailView;
use app\helpers\ArrayHelper;

/**
 * @var View $this
 * @var Groups $group
 **/
?>
<?= DetailView::widget([
	'model' => $group,
	'attributes' => [
		'id',
		'name',
		[
			'label' => 'Тип',
			'value' => $group->relGroupTypes->name
		],
		[
			'label' => 'Лидер',
			'value' => implode(", ", ArrayHelper::getColumn($group->leaders, 'username'))
		],
		'comment'
	]
]); ?>

