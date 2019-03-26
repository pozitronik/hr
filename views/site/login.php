<?php
declare(strict_types = 1);

/**
 * Шаблон страницы авторизации
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var LoginForm $login
 */

use app\models\site\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход';
?>
<div class="cls-content-sm panel">
	<div class="panel-body">
		<div class="mar-ver pad-btm">
			<div class="row">
				<div class="col-md-4"></div>
			</div>
			<h1 class="h3">Вход</h1>
			<p>Приготовьтесь войти в неизведанное</p>
		</div>
		<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= $form->field($login, 'login')
				->textInput(['placeholder' => 'Пожалуйста, введите логин']) ?>
		</div>
		<div class="form-group">
			<?= $form->field($login, 'password')
				->passwordInput(['placeholder' => 'Пожалуйста, введите пароль']) ?>
		</div>
		<div class="checkbox pad-btm text-left">
			<?= $form->field($login, 'rememberMe')
				->checkbox() ?>
		</div>
		<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
		<?php ActiveForm::end(); ?>
	</div>
<!--	<div class="pad-all">
		<?= Html::a('Восстановление пароля', ['site/restore-password'], ['class' => 'btn-link mar-rgt']) ?>
		<?= Html::a('Регистрация', ['site/register'], ['class' => 'btn-link mar-lft']) ?>
	</div>
	-->
</div>