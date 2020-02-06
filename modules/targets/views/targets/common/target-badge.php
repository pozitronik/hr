<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets[] $models
 */

use app\modules\targets\assets\TargetsAsset;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

TargetsAsset::register($this);
?>

<?= BadgeWidget::widget([
	'models' => $models,
	'attribute' => 'name',
	"badgeOptions" => [
		'class' => "badge badge-target"
	],
	'itemsSeparator' => false,
	"optionsMap" => RefTargetsTypes::colorStyleOptions(),
	"optionsMapAttribute" => 'type',
	'linkScheme' => [TargetsModule::to('targets/update'), 'id' => 'id'],
	'badgePostfix' => function(Targets $model) {
		return $this->render('mirror-badge', [
			'model' => $model,
			'spanned' => true
		]);
	}
]) ?>