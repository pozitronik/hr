<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var Groups $group
 * @var array $ierarchy Рекурсивная иерархия для навигации в заголовке
 */

if ($group->getRelUsers()->count() > 0) {
	echo $this->render('index', [
		'model' => $group,
		'selectorInPanel' => false,
		'showRolesSelector' => false,
		'showDropColumn' => false,
		'heading' => Breadcrumbs::widget([
			'homeLink' => false,
			'links' => $ierarchy
		])
	]);
}

/** @var Groups[] $subgroups */
$ierarchy[] = [];
$subgroups = $group->getRelChildGroups()->orderBy('name')->active()->all();//Группы нижестоящего уровня
foreach ($subgroups as $subgroup) {
	ArrayHelper::setLast($ierarchy, ['label' => $subgroup->name, 'url' => ['/admin/groups/update', 'id' => $subgroup->id]]);
	echo $this->render('index_tree', [
		'group' => $subgroup,
		'ierarchy' => $ierarchy
	]);
}
ArrayHelper::setLast($ierarchy);

