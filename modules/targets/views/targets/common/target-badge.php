<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets[] $models
 * @var bool $onlyMirrored -- показывать данные назначений только для зеркальных целей
 */

use app\modules\targets\assets\TargetsAsset;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use pozitronik\widgets\BadgeWidget;
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
	'badgePostfix' => function(Targets $model) use ($onlyMirrored) {
		return (!$onlyMirrored || $model->isMirrored)?$this->render('mirror-badge', [
			'model' => $model,
			'spanned' => true
		]):null;
	}
]) ?>