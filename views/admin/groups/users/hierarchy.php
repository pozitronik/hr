<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use yii\web\View;

/**
 * @var View $this
 * @var Groups $group
 * @var boolean $showRolesSelector Показывать челикам выбиралку ролей (может тормозить)
 */

$this->title = 'Иерархия пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/admin/groups']];
$this->params['breadcrumbs'][] = ['label' => $group->name, 'url' => ['/admin/groups/update', 'id' => $group->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-md-12">
				<?= $this->render('index_tree', [
					'group' => $group,
					'showRolesSelector' => $showRolesSelector,
					'hierarchy' => [['label' => null === $group->type?$group->name:"{$group->relGroupTypes->name}: $group->name", 'url' => ['/admin/groups/update', 'id' => $group->id]]]
				]); ?>
			</div>
		</div>
	</div>
</div>
