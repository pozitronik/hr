<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ScoreProperty $model
 * @var string $scoreAttributeName
 * @var string $commentAttributeName
 */

use app\models\dynamic_attributes\types\ScoreProperty;
use kartik\rating\StarRating;
use yii\web\View;

?>

<div class="panel panel-score panel-info">
	<div class="panel-heading">
		<div class="panel-title"><?= $model->getAttributeLabel($scoreAttributeName) ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= StarRating::widget([
					'name' => '$scoreAttributeName',
					'value' => $model->$scoreAttributeName,
					'pluginOptions' => [
						'size' => StarRating::SIZE_SMALL,
						'displayOnly' => false,
						'stars' => 5,
						'min' => 0,
						'max' => 5,
						'step' => 1,

						'starCaptions' => [
							0 => 'N/A',
							1 => 'Very Poor',
							2 => 'Poor',
							3 => 'Ok',
							4 => 'Good',
							5 => 'Very Good',
						],
					],
				]); ?>
			</div>
		</div>
		<?php if (!empty($model->$commentAttributeName)): ?>
			<div class="row">
				<div class="col-md-12">
					<?= $model->$commentAttributeName; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>