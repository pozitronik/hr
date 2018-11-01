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

$random1 = random_int(1, 100000);
$random2 = random_int(1, 100000);
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

<div class="panel panel-info panel-colorful">
	<div class="pad-all">
		<p class="text-lg text-semibold"><i class="glyphicon glyphicon-ruble"></i> Показометр</p>
		<p class="mar-no">
			<span class="pull-right text-bold">
				<?= $random1 ?>&#8381;</span>
			Заработано
		</p>
		<p class="mar-no">
			<span class="pull-right text-bold">
				<?= $random2 ?>&#8381;</span>
			Истрачено
		</p>
	</div>
</div>

