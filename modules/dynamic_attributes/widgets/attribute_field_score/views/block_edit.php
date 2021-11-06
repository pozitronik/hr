<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $scoreAttributeName
 * @var string $commentAttributeName
 * @var string $attribute
 */

use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use kartik\base\BootstrapInterface;
use yii\helpers\Html;
use kartik\rating\StarRating;
use yii\web\View;

?>

<div class="panel panel-score">
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
						'size' => BootstrapInterface::SIZE_SMALL,
						'displayOnly' => false,
						'stars' => 5,
						'min' => 0,
						'max' => 5,
						'step' => 1,
						'clearCaption' => 'N/A',
						'starCaptions' => [
							0 => 'N/A',
							1 => '1/5',
							2 => '2/5',
							3 => '3/5',
							4 => '4/5',
							5 => '5/5'
						]
					]
				]) ?>
				<?= Html::textInput("DynamicAttributeProperty[$model->id][$commentAttributeName]", ArrayHelper::getValue($model->$attribute, $commentAttributeName), ['placeholder' => 'Комментарий', 'maxlength' => 255, 'style' => 'width:100%']) ?>
			</div>
		</div>
	</div>
</div>