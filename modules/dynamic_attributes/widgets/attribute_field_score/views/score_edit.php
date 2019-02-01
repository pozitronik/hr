<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $attribute
 */

use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\web\View;

?>

<div class="panel panel-score-summary">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<?= $this->render('block_edit', [
					'model' => $model,
					'scoreAttributeName' => 'selfScoreValue',
					'commentAttributeName' => 'selfScoreComment',
					'attribute' => $attribute
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $this->render('block_edit', [
					'model' => $model,
					'scoreAttributeName' => 'tlScoreValue',
					'commentAttributeName' => 'tlScoreComment',
					'attribute' => $attribute
				]) ?>

			</div>
			<div class="col-md-4">
				<?= $this->render('block_edit', [
					'model' => $model,
					'scoreAttributeName' => 'alScoreValue',
					'commentAttributeName' => 'alScoreComment',
					'attribute' => $attribute
				]) ?>
			</div>
		</div>
	</div>
</div>


