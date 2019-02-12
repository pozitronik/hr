<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var $showRolesSelector bool
 * @var array $hierarchy
 */

use app\modules\groups\models\Groups;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Breadcrumbs;

if (1 === count($hierarchy)) {
	$this->title = "Иерархия пользователей в группе {$model->name}";
	$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
	$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
	$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/groups/groups/profile', 'id' => $model->id]];
	$this->params['breadcrumbs'][] = $this->title;
}
?>

<?php if ($model->usersCount > 0): ?>
	<?= $this->render('grid', [
		'model' => $model,
		'provider' => new ActiveDataProvider([
			'query' => $model->getRelUsers()->active()
		]),
		'showUserSelector' => false,
		'showRolesSelector' => $showRolesSelector,
		'showDropColumn' => false,
		'heading' => Breadcrumbs::widget([
			'encodeLabels' => false,
			'homeLink' => false,
			'links' => $hierarchy
		])
	]); ?>
<?php endif; ?>
<?php foreach ($model->getRelChildGroups()->orderBy('name')->active()->all() as $subgroup): ?>
	<?php $hierarchy[] = [
		'label' => null === $subgroup->type?$subgroup->name:"{$subgroup->relGroupTypes->name}: $subgroup->name",
		'url' => ['/groups/groups/profile', 'id' => $subgroup->id]
	]; ?>

	<?= $this->render('hierarchy', [
		'model' => $subgroup,
		'showRolesSelector' => $showRolesSelector,
		'hierarchy' => $hierarchy
	]); ?>
	<?php array_pop($hierarchy) ?>
<?php endforeach; ?>
