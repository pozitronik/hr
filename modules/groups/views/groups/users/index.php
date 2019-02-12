<?php

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 */
declare(strict_types = 1);

use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\NavigationMenuWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title = "Пользователи в группе {$model->name}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/groups/groups/profile', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
$countLabel = (($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= NavigationMenuWidget::widget([
				'model' => $model
			]); ?>
		</div>
		<h3 class="panel-title"><?= $this->title.' '.$countLabel; ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= $this->render('grid', [
					'model' => $model,
					'provider' => $provider,
					'showUserSelector' => true,
					'showRolesSelector' => true,
					'showDropColumn' => true,
					'heading' => false
				]) ?>
			</div>
		</div>
	</div>
</div>
