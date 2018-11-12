<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Bookmarks[] $bookmarks
 * @var Bookmarks $model
 */

use app\models\users\Bookmarks;
use yii\web\View;
use yii\helpers\Html;

?>
<ul class="list-unstyled">
	<li class="dropdown-header">Избранное
		<?= Html::a('Добавить', "javascript:add_bookmark('{$model->route}','{$model->name}','{$model->type}')", ['class' => 'btn btn-default btn-xs pull-right', 'name' => 'add-bookmark']) ?>
	</li>
	
	<?php foreach ($bookmarks as $bookmark): ?>
		<li><?= Html::a($bookmark->typeSpan.$bookmark->name, [$bookmark->route]) ?></li>
	<?php endforeach; ?>
</ul>