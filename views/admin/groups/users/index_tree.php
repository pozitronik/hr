<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var Groups $group
 */

if ($group->getRelUsers()->count() > 0) {
	echo $this->render('index', [
		'model' => $group,
		'selectorInPanel' => false,
		'rolesSelector' => false,
		'heading' => Breadcrumbs::widget([
			'homeLink' => false,
			'links' => [['label' => $group->name, 'url' => ['/admin/groups/update', 'id' => $group->id]]]
		])
	]);
}

/** @var Groups[] $subgroups */
$ierarchy[] = [];
$subgroups = $group->getRelChildGroups()->orderBy('name')->active()->all();//Группы нижестоящего уровня
foreach ($subgroups as $subgroup) {
	echo $this->render('index_tree', [
		'group' => $subgroup
	]);
}
ArrayHelper::setLast($ierarchy);

?>
