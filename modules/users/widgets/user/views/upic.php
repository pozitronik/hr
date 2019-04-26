<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var boolean $boss
 */

use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use yii\web\View;
use yii\helpers\Html;

$borderStyle = (CurrentUser::Id() === $model->id)?'img-border-current':'img-border';
?>
<?= Users::a(Html::img($model->avatar, ['class' => "img-circle img-user media-object {$borderStyle}", 'alt' => $model->username, 'title' => $model->username]), ['users/profile', 'id' => $model->id]) ?>