<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\modules\groups\models\Groups;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var Groups $group
 * @var array $hierarchy Рекурсивная иерархия для навигации в заголовке
 * @var boolean $showRolesSelector Показывать челикам выбиралку ролей (может тормозить)
 */

if ($group->usersCount > 0) {
	echo $this->render('index', [
		'model' => $group,
		'selectorInPanel' => false,
		'showRolesSelector' => $showRolesSelector,
		'showDropColumn' => false,
		'heading' => Breadcrumbs::widget([
			'homeLink' => false,
			'links' => $hierarchy
		])
	]);
}

$hierarchy[] = [];
/** @var Groups[] $subgroups */
$subgroups = $group->getRelChildGroups()->orderBy('name')->active()->all();//Группы нижестоящего уровня
foreach ($subgroups as $subgroup) {
	ArrayHelper::setLast($hierarchy, ['label' => null === $subgroup->type?$subgroup->name:"{$subgroup->relGroupTypes->name}: $subgroup->name", 'url' => ['/admin/groups/update', 'id' => $subgroup->id]]);
	echo $this->render('index_tree', [
		'group' => $subgroup,
		'hierarchy' => $hierarchy,
		'showRolesSelector' => $showRolesSelector
	]);
}
ArrayHelper::setLast($hierarchy);

