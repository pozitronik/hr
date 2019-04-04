<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var TimelineEntry $entry ;
 */

use app\models\prototypes\TimelineEntry;
use yii\web\View;

?>

<div class="timeline-entry">
	<div class="timeline-stat">
		<div class="timeline-icon"><?= $entry->icon ?>
		</div>
		<div class="timeline-time"><?= $entry->time ?></div>
	</div>
	<div class="timeline-label">
		<p class="mar-no pad-btm">
			<a href="#" class="text-semibold"><i><?= $entry->header ?></i></a></p>
		<span><?= $entry->content ?></span>
	</div>
</div>
