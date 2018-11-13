<?php
declare(strict_types = 1);

/**
 * Главная страница пользователя при входе
 * @var View $this
 * @var Users $model
 */

use app\models\users\Users;
use yii\web\View;
use app\widgets\group\GroupWidget;
$this->title = 'Мои команды';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach ($model->relGroups as $group): ?>
	<?= GroupWidget::widget([
		'group' => $group,
		'user' => $model
	]); ?>
<?php endforeach;?>
