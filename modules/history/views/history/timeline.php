<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var HistoryEventInterface[] $timeline
 */

use app\modules\history\models\HistoryEventInterface;
use app\modules\history\widgets\timeline_entry\TimelineEntryWidget;
use yii\web\View;

?>

<div class="timeline">

	<!-- Timeline header -->
	<div class="timeline-header">
		<div class="timeline-header-title bg-primary">Начало</div>
	</div>
	<?php foreach ($timeline as $populatedEvent): ?>
		<?= TimelineEntryWidget::widget([
			'entry' => $populatedEvent->asTimelineEntry()
		]) ?>

	<?php endforeach; ?>

</div>
