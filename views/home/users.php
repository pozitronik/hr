<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var string $groupName
 * @var string $positionTypeName
 */

use app\widgets\user_card\UserCardWidget;
use app\modules\users\models\UsersSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = "Сводка по сотрудникам {$groupName}/{$positionTypeName}";
$this->params['breadcrumbs'][] = $this->title;
?>

	<div class="panel">
		<div class="panel-heading">
			<div class="panel-control">
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
				</div>
			</div>
		</div>
	</div>

<?php foreach ($dataProvider->models as $model): ?>
	<?= UserCardWidget::widget([
		'user' => $model,
	]); ?>

<?php endforeach; ?>