<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ScoreProperty $model
 * @var string $caption
 */

use app\models\dynamic_attributes\types\ScoreProperty;
use yii\web\View;

?>

<div class="panel panel-score-summary panel-primary">
	<div class="panel-heading">
		<div class="panel-title"><?= $caption ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<?= $this->render('edit_block', [
					'model' => $model,
					'scoreAttributeName' => 'selfScoreValue',
					'commentAttributeName' => 'selfScoreComment'
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $this->render('edit_block', [
					'model' => $model,
					'scoreAttributeName' => 'tlScoreValue',
					'commentAttributeName' => 'tlScoreComment'
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $this->render('edit_block', [
					'model' => $model,
					'scoreAttributeName' => 'alScoreValue',
					'commentAttributeName' => 'alScoreComment'
				]) ?>
			</div>
		</div>
	</div>
</div>


