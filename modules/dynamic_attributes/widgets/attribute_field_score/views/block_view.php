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
use kartik\rating\StarRating;
use yii\web\JsExpression;
use yii\web\View;

?>

<div class="panel panel-score">
	<div class="panel-heading">
		<div class="panel-title"><?= null === $model->$attribute?null:$model->$attribute->getAttributeLabel($scoreAttributeName) ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= StarRating::widget([
					'name' => "DynamicAttributeProperty[$model->id][$scoreAttributeName]",
					'value' => $value = ArrayHelper::getValue($model->$attribute, $scoreAttributeName),
					'pluginOptions' => [
						'size' => (INF === $value)?BootstrapInterface::SIZE_TINY:BootstrapInterface::SIZE_SMALL,
						'displayOnly' => true,
						'stars' => 5,
						'step' => 0.1,
						'clearCaption' => (INF === $value)?'Infinite':'N/A',
						'starCaptions' => new JsExpression("function(val){return val}")
					]
				]) ?>
			</div>
		</div>
		<?php if (null !== $comment = ArrayHelper::getValue($model->$attribute, $commentAttributeName)): ?>
			<div class="row">
				<div class="col-md-12">
					<?= $comment ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>