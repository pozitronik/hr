<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets[] $models
 */

use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositions;
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
	'badgePostfix' => static function(Targets $model) {
		$mirroredData = [];
		if ($model->isMirrored) {
			if ([] !== $model->relGroups) {
				$mirroredData[] = BadgeWidget::widget([
					'models' => (array)$model->relGroups,
					'attribute' => 'name',
					'useBadges' => false,
					'itemsSeparator' => ', ',
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => [TargetsModule::to('targets/group'), 'id' => 'id'],
				]);
			}
			if ([] !== $model->relUsers) {
				$mirroredData[] = BadgeWidget::widget([
					'models' => (array)$model->relUsers,
					'attribute' => 'username',
					'useBadges' => false,
					'itemsSeparator' => ', ',
					"optionsMap" => RefUserPositions::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => [TargetsModule::to('targets/home'), 'id' => 'id'],
				]);
			}
		}
		return ([] !== $mirroredData)?"<span class='badge-target-mirrors'>".BadgeWidget::widget([
				'models' => $mirroredData,
				'useBadges' => false,
				'itemsSeparator' => ', ',
				'prefix' => "Зеркалится: "
			])."</span>":"";

	}
]) ?>