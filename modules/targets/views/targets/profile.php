<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets $model
 */

use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\TargetsModule;
use app\modules\targets\models\Targets;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;

$this->title = $model->isNewRecord?'Добавление задания':"Задание {$model->name}";
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= TargetNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="clearfix"></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<label>Тип:</label>
				<?= BadgeWidget::widget([
					'models' => $model->relTargetsTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					"optionsMap" => RefTargetsTypes::colorStyleOptions(),
					"badgeOptions" => [
						'class' => 'badge target-type-name'
					],
					'linkScheme' => [TargetsModule::to(), 'TargetsSearch[type]' => 'id']
				]) ?>
			</div>

		</div>
	</div>
В разработке

</div>
