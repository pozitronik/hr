<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use yii\web\View;

/**
 * @var View $this
 * @var Groups $model
 */

if ($model->getRelUsers()->count() > 0) {
	echo $this->render('index', [
		'model' => $model,
		'selectorInPanel' => false,
		'heading' => false
	]);
}

$subgroups = $model->getRelChildGroups()->orderBy('name')->active()->all();//Группы нижестоящего уровня
foreach ($subgroups as $subgroup) {
	echo $this->render('index', [
		'model' => $subgroup,
		'selectorInPanel' => false,
		'heading' => "Йохохо, ублюдки"
	]);
}

?>
