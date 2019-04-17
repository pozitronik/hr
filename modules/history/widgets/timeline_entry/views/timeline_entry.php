<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var TimelineEntry $entry
 */

use app\helpers\ArrayHelper;
use app\modules\history\models\TimelineEntry;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="timeline-entry">
	<div class="timeline-stat">
		<div class="timeline-icon"><?= $entry->icon ?></div>
		<div class="timeline-time"><?= $entry->time ?></div>
	</div>
	<div class="timeline-label">
		<p class="mar-no pad-btm">
			<span class="text-semibold"><i><?= $entry->caption ?></i></span>
			<?= Html::a(ArrayHelper::getValue($entry->user, 'username'), (null === $entry->user)?'#':['/users/users/profile', 'id' => $entry->user->id], ['class' => 'text-semibold pull-right']) ?>
		</p>
		<span><?= $entry->content ?></span>
	</div>
</div>
