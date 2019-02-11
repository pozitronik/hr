<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var DynamicAttributes $attribute
 */

use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\Users;
use app\widgets\radar\RadarWidget;
use yii\web\View;
use yii\helpers\Html;

$this->title = "{$user->username}: Диаграмма атрибутов {$attribute->name}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/users/users']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['/users/users/profile', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>
	<div class="panel-body">
		<?= RadarWidget::widget(compact('attribute', 'user')); ?>
	</div>
</div>




