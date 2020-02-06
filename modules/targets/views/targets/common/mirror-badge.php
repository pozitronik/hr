<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets $model
 */

use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\targets\assets\TargetsAsset;
use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

TargetsAsset::register($this);

?>
<?= BadgeWidget::widget([
	'models' => (array)$model->relGroups,
	'attribute' => 'name',
	'useBadges' => true,
	'itemsSeparator' => false,
	"badgeOptions" => [
		'class' => "badge badge-target"
	],
	"optionsMap" => RefGroupTypes::colorStyleOptions(),
	"optionsMapAttribute" => 'type',
	'linkScheme' => [TargetsModule::to('targets/group'), 'id' => 'id'],
]); ?>

<?= BadgeWidget::widget([
	'models' => (array)$model->relUsers,
	'attribute' => 'username',
	'useBadges' => true,
	'itemsSeparator' => false,
	"badgeOptions" => [
		'class' => "badge badge-target"
	],
	"optionsMap" => RefUserPositions::colorStyleOptions(),
	"optionsMapAttribute" => 'position',
	'linkScheme' => [TargetsModule::to('targets/user'), 'id' => 'id'],
]) ?>
