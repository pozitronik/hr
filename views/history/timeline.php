<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var HistoryEventInterface[] $timeline
 */

use app\models\prototypes\HistoryEventInterface;
use app\modules\users\widgets\user\UserWidget;
use yii\web\View;

?>

<div class="timeline">

	<!-- Timeline header -->
	<div class="timeline-header">
		<div class="timeline-header-title bg-primary">Начало</div>
	</div>
	<?php foreach ($timeline as $populatedEvent): ?>

		<div class="timeline-entry">
			<div class="timeline-stat">
				<div class="timeline-icon"><?= UserWidget::widget([
						'user' => $populatedEvent->subject,
						'view' => 'short'
					]) ?>
				</div>
				<div class="timeline-time"><?= $populatedEvent->eventTime ?></div>
			</div>
			<div class="timeline-label">
				<p class="mar-no pad-btm">


					<a href="#" class="text-semibold"><i><?= $populatedEvent->objectName ?></i></a></p>
				<blockquote class="bq-sm bq-open mar-no"><?= $populatedEvent->action ?></blockquote>
			</div>
		</div>
	<?php endforeach; ?>

</div>
