<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Bookmarks[] $bookmarks
 * @var Bookmarks $model
 */

use pozitronik\helpers\ArrayHelper;
use app\modules\users\models\Bookmarks;
use yii\web\View;
use yii\helpers\Html;

$bookmark_exists = ArrayHelper::array_find_deep(ArrayHelper::getColumn($bookmarks, 'route'), $model->route);
/*todo: букмарка не срабатывает, если в имени есть кавычка*/
?>
<ul class="list-unstyled" id="nav-bookmarks">
	<li class="dropdown-header">Избранное
		<?= $bookmark_exists?Html::a('Удалить', "javascript:remove_bookmark('{$model->route}')", ['class' => 'btn btn-default btn-xs pull-right', 'name' => 'remove-bookmark']):Html::a('Добавить', "javascript:add_bookmark('{$model->route}','{$model->name}','{$model->type}')", ['class' => 'btn btn-default btn-xs pull-right', 'name' => 'add-bookmark']) ?>
	</li>

	<?php foreach ($bookmarks as $bookmark): ?>


		<li <?=($model->route === $bookmark->route)?'class="selected"':'class="not-selected"' ?> data-href="<?= $bookmark->route ?>"><?= Html::a($bookmark->typeSpan.$bookmark->name, [$bookmark->route]) ?></li>
	<?php endforeach; ?>
</ul>