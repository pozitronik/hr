<?php
declare(strict_types = 1);

use app\modules\groups\models\Groups;
use yii\web\View;
use kartik\detail\DetailView;
use app\helpers\ArrayHelper;

/**
 * @var View $this
 * @var Groups $group
 **/

$leaderName = implode(", ", ArrayHelper::getColumn($group->leaders, 'username'));
$leaderName = empty($leaderName)?'Не указан':$leaderName;
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
			'value' => $leaderName
		],
		[
			'attribute' => 'comment'
		]
	]
]); ?>

