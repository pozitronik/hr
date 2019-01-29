<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $scoreAttributeName
 * @var string $commentAttributeName
 * @var string $attribute
 */

use app\helpers\ArrayHelper;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\helpers\Html;
use kartik\rating\StarRating;
use yii\web\View;

?>

<div class="panel panel-score panel-info">
	<div class="panel-heading">
		<div class="panel-title"><?= $model->$attribute->getAttributeLabel($scoreAttributeName) ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= StarRating::widget([
					'name' => "DynamicAttributeProperty[$model->id][$scoreAttributeName]",
					'value' => ArrayHelper::getValue($model->$attribute, $scoreAttributeName),
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
							5 => 'Very Good'
						]
					]
				]); ?>
				<?= Html::textInput("DynamicAttributeProperty[$model->id][$commentAttributeName]", ArrayHelper::getValue($model->$attribute, $commentAttributeName), ['placeholder' => 'Комментарий', 'maxlength' => 255, 'style' => 'width:100%']); ?>
			</div>
		</div>
	</div>
</div>