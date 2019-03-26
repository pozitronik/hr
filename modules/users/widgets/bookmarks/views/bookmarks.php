<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Bookmarks[] $bookmarks
 * @var Bookmarks $currentBookmark
 */

use app\modules\users\models\Bookmarks;
use yii\web\View;
?>

<?= $this->render('list', [
	'bookmarks' => $bookmarks,
	'model' => $currentBookmark
]) ?>