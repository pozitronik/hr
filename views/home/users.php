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
use yii\helpers\Url;
use yii\web\View;

$this->title = "Сводка по сотрудникам {$groupName}/{$positionTypeName}";
$this->params['breadcrumbs'][] = $this->title;
?>

	<div class="panel">
		<div class="panel-heading">
			<div class="panel-control">
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title) ?><?= Html::a("<div class='pull-right'>Таблица</div>", Url::current(['t' => 1])) ?></h3>
		</div>

	</div>

<?php foreach ($dataProvider->models as $model): ?>
	<?= UserCardWidget::widget([
		'user' => $model
	]) ?>

<?php endforeach; ?>