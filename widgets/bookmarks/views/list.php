<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Bookmarks[] $bookmarks
 */

use app\models\users\Bookmarks;
use yii\web\View;
use yii\helpers\Html;

?>
<ul class="list-unstyled">
	<li class="dropdown-header">Избранное</li>
<?php foreach ($bookmarks as $bookmark): ?>
	<li><?= Html::a($bookmark->typeSpan.$bookmark->name,$bookmark->route)?></li>
<?php endforeach; ?>
</ul>