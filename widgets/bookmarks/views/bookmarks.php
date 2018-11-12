<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\users\Bookmarks;
use app\models\users\Users;
use yii\web\View;

$model = new Bookmarks([

	'route' => Yii::$app->requestedRoute,
	'name' => $this->title,
	'type' => Bookmarks::TYPE_DEFAULT
]);
?>
<?= $this->render('list', [
	'bookmarks' => $user->options->bookmarks,
	'model' => $model
]); ?>