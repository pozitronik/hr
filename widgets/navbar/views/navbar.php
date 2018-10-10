<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\users\Users;
use yii\web\View;
use yii\helpers\Html;
use app\widgets\admin_panel\AdminPanelWidget;
?>

<header id="navbar">
	<div id="navbar-container" class="boxed">
		<!--Brand logo & name-->
		<!--================================-->
		<div class="navbar-header">
			<ul class="nav navbar-top-links pull-left">
				<!--Mega dropdown-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<li class="mega-dropdown">
					<a href="#" class="mega-dropdown-toggle">
						<div class="navbar-header">
							<div class="navbar-brand">
								<div class="start"></div>
							</div>
						</div>
					</a>
					<div class="dropdown-menu mega-dropdown-menu">
						<div class="row">
							<div class="col-sm-4 col-md-3">

								<!--Mega menu list-->
								<ul class="list-unstyled">
									<li class="dropdown-header">Навигация</li>
									<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no">Команды</p><small class="text-muted">Рабочие группы в сфере моей ответственности</small></div>', ["home/index"]); ?></li>
									<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no">Компетенции</p><small class="text-muted">Мои полномочия, обязанности и задачи</small></div>', ["home/index"]); ?></li>
									<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no">Матрица ресурсов</p><small class="text-muted">Матрица связей рабочих групп</small></div>', ["home/matrix"]); ?></li>
								</ul>

							</div>
							<div class="col-sm-4 col-md-3">

								<!--Mega menu list-->
								<ul class="list-unstyled">
									<li class="dropdown-header">Избранное</li>
									<li><a href="#"><span class="pull-right label label-danger">Важно</span>Задачи</a>
									</li>
									<li><a href="#">Календарь</a></li>
								</ul>
								<p class="pad-top mar-top bord-top text-sm">Чтобы добавить текущую страницу в избранное нажмите <?= Html::a('здесь', ['#'], ['class' => 'btn btn-default btn-xs']); ?></p>
							</div>
							<?php if($user->is('admin')): ?>
								<?= AdminPanelWidget::widget([
									'mode' => AdminPanelWidget::MODE_PANEL
								]) ?>
							<?php endif; ?>
						</div>
					</div>
				</li>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End mega dropdown-->

			</ul>
		</div>
		<!--================================-->
		<!--End brand logo & name-->

		<!--Navbar Dropdown-->
		<!--================================-->
		<div class="navbar-content clearfix">

			<ul class="nav navbar-top-links pull-right">


				<!--User dropdown-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<li id="dropdown-user" class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
						<div class="username hidden-xs"><?= $user->username; ?></div>
						<span class="pull-right">
							<img class="img-circle img-user media-object" src="<?= $user->avatar; ?>" alt="Profile Picture">
							<i class="demo-pli-male ic-user"></i>
						</span>
					</a>


					<div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">

						<!-- Dropdown heading  -->
						<div class="pad-all bord-btm">
							<?= $user->comment; ?>
						</div>


						<!-- User dropdown menu -->
						<ul class="head-list">
							<li>
								<?= Html::a("Профиль", ["home/profile"]); ?>
							</li>
							<li>
								<?= Html::a('<span class="badge badge-danger pull-right">9</span>Сообщения', ["home/messenger"]); ?>

							</li>
							<li>
								<?= Html::a("Настройки", ["home/settings"]); ?>
							</li>
							<li>
								<?= Html::a("Помощь", ["home/help"]); ?>
							</li>
						</ul>

						<!-- Dropdown footer -->
						<div class="pad-all text-right">
							<?= Html::a("Выйти", ['site/logout'], ['class' => 'btn btn-primary']) ?>
						</div>
					</div>
				</li>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End user dropdown-->

			</ul>
		</div>
		<!--================================-->
		<!--End Navbar Dropdown-->

	</div>
</header>

