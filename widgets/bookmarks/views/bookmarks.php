<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\users\Bookmarks;
use app\models\users\Users;
use yii\helpers\Url;
use yii\web\View;

$model = new Bookmarks([

	'route' => Url::toRoute(array_merge([Yii::$app->requestedRoute] + Yii::$app->requestedAction->controller->actionParams)),
	'name' => $this->title?:Yii::$app->requestedRoute,
	'type' => Bookmarks::TYPE_DEFAULT
]);
?>
<?= $this->render('list', [
	'bookmarks' => $user->options->bookmarks,
	'model' => $model
]); ?>