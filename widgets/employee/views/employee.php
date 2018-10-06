<?php
declare(strict_types = 1);

/* @var View $this */

/* @var Users $model */

use app\models\users\Users;
use yii\web\View;
use yii\helpers\Html;

?>
<div class="fixed-fluid">
	<div class="fixed-sm-200 fixed-lg-250 pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<img src="<?= $model->avatar; ?>" class="img-lg img-border img-circle" alt="<?= $model->username; ?>">
				</div>
				<h4 class="text-lg mar-no"><?= $model->username; ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no">Личная информация</p>
				<ul class="list-group bg-trans text-sm">
					<li class="list-group-item list-item-sm">
						<i class="fa fa-id-card-o fa-fw" title="Табельный номер"></i>
						<?= Html::a($model->personal_number??'Не заполнено', null, [
								'contenteditable' => true,
								'id' => 'user_personnel_number_edit',
								'data-id' => $model->id,
								'title' => 'Нажмите для редактирования',
								'class' => 'editable'
							]
						); ?>
					</li>
					<li class="list-group-item list-item-sm">
						<i class="fa fa-phone fa-fw" title="Телефон"></i>
						<?= Html::a($model->phone??'Не заполнено', null, [
								'contenteditable' => true,
								'id' => 'user_phone_edit',
								'data-id' => $model->id,
								'title' => 'Нажмите для редактирования',
								'class' => 'editable'
							]
						); ?>
					</li>
					<li class="list-group-item list-item-sm">
						<i class="fa fa-at fa-fw" title="E-mail"></i>
						<?= Html::a($model->email??'Не заполнено', null,
							[
								'contenteditable' => empty($model->email),
								'id' => 'user_email_edit',
								'data-id' => $model->id,
								'title' => empty($model->email)?'Нажмите для редактирования':'Редактирование невозможно',
								'class' => 'editable'
							]
						); ?>
					</li>
					<li class="list-group-item list-item-sm">
						<i class="fa fa-key fa-fw"></i>
						<?= Html::a('Изменить пароль', ['users/change-password']); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>