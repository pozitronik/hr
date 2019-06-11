<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var RibbonPage[] $pages
 * @var string $options
 */

use app\widgets\ribbon\RibbonPage;
use yii\web\View;

?>

<div <?= $options ?>>
	<div class="panel-heading">
		<div class="panel-control">
			<ul class="nav nav-tabs">
				<?php foreach ($pages as $page): ?>
					<li class="ribbon-tab <?= $page->activeString ?>">
						<a href="#<?= $page->id ?>" data-toggle="tab" aria-expanded="<?= $page->expandedString ?>">
							<?= $page->caption ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<?php foreach ($pages as $page): ?>
				<div id="<?= $page->id ?>" class="tab-pane fade <?= $page->activeString ?> <?= $page->inString ?>">
					<?= $page->content ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

