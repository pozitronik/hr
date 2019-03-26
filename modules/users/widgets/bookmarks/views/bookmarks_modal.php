<?php
declare(strict_types = 1);

/**
 * Пока неиспользуемая модальная форма добавления (с редактором)
 * @var View $this
 * @var Users $user
 */

use app\modules\users\models\Bookmarks;
use app\modules\users\models\Users;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

$model = new Bookmarks([
	'route' => Yii::$app->requestedRoute,
	'name' => $this->title,
	'type' => Bookmarks::TYPE_DEFAULT
]);

?>
<?= $this->render('list', [
	'bookmarks' => $user->options->bookmarks,
	'model' => $model
]) ?>
<?php Modal::begin([
	'header' => 'Добавление закладки',
	'toggleButton' => [
		'class' => 'btn btn-default btn-xs pull-right',
		'label' => 'Добавить'
	]
]);
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'route')->textInput() ?>
<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'type')->widget(Select2::class, [
	'data' => Bookmarks::Types
]) ?>

	<div class="form-group">
		<?= Html::a('Добавить', "javascript:add_bookmark('{$model->route}','{$model->name}','{$model->type}')", ['class' => 'btn btn-primary', 'name' => 'add-bookmark']) ?>
	</div>

<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>