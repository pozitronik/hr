<?php
declare(strict_types = 1);

/**
 * Главная страница пользователя при входе
 * @var View $this
 * @var Users $model
 */

use app\modules\users\models\Users;
use yii\web\View;
use app\modules\groups\widgets\group\GroupWidget;

$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach ($model->relGroups as $group): ?>
	<?= GroupWidget::widget([
		'group' => $group
	]) ?>
<?php endforeach; ?>
