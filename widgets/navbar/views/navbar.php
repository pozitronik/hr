<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\models\users\Users;
use yii\web\View;
use yii\helpers\Html;

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
					<div class="dropdown-menu mega-dropdown-menu" style="min-width: 800px;">
						<div class="row">
							<div class="col-sm-4 col-md-3">

								<!--Mega menu list-->
								<ul class="list-unstyled">
									<li class="dropdown-header"><i class="demo-pli-file icon-fw"></i> Pages</li>
									<li><a href="#">Profile</a></li>
									<li><a href="#">Search Result</a></li>
									<li><a href="#">FAQ</a></li>
									<li><a href="#">Sreen Lock</a></li>
									<li><a href="#" class="disabled">Disabled</a></li>
								</ul>

							</div>
							<div class="col-sm-4 col-md-3">

								<!--Mega menu list-->
								<ul class="list-unstyled">
									<li class="dropdown-header"><i class="demo-pli-mail icon-fw"></i> Mailbox</li>
									<li><a href="#"><span class="pull-right label label-danger">Hot</span>Indox</a>
									</li>
									<li><a href="#">Read Message</a></li>
									<li><a href="#">Compose</a></li>
								</ul>
								<p class="pad-top mar-top bord-top text-sm">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
							</div>
							<div class="col-sm-4 col-md-3">
								<!--Mega menu list-->
								<ul class="list-unstyled">
									<li>
										<a href="#" class="media mar-btm">
											<span class="badge badge-success pull-right">90%</span>
											<div class="media-left">
												<i class="demo-pli-data-settings icon-2x"></i>
											</div>
											<div class="media-body">
												<p class="text-semibold text-dark mar-no">Data Backup</p>
												<small class="text-muted">This is the item description</small>
											</div>
										</a>
									</li>
									<li>
										<a href="#" class="media mar-btm">
											<div class="media-left">
												<i class="demo-pli-support icon-2x"></i>
											</div>
											<div class="media-body">
												<p class="text-semibold text-dark mar-no">Support</p>
												<small class="text-muted">This is the item description</small>
											</div>
										</a>
									</li>
									<li>
										<a href="#" class="media mar-btm">
											<div class="media-left">
												<i class="demo-pli-computer-secure icon-2x"></i>
											</div>
											<div class="media-body">
												<p class="text-semibold text-dark mar-no">Security</p>
												<small class="text-muted">This is the item description</small>
											</div>
										</a>
									</li>
									<li>
										<a href="#" class="media mar-btm">
											<div class="media-left">
												<i class="demo-pli-map-2 icon-2x"></i>
											</div>
											<div class="media-body">
												<p class="text-semibold text-dark mar-no">Location</p>
												<small class="text-muted">This is the item description</small>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="col-sm-12 col-md-3">
								<p class="dropdown-header"><i class="demo-pli-file-jpg icon-fw"></i> Gallery</p>
								<ul class="list-unstyled list-inline text-justify">

									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-2.jpg" alt="thumbs">
									</li>
									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-3.jpg" alt="thumbs">
									</li>
									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-1.jpg" alt="thumbs">
									</li>
									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-4.jpg" alt="thumbs">
									</li>
									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-5.jpg" alt="thumbs">
									</li>
									<li class="pad-btm">
										<img src="img//thumbs/mega-menu-6.jpg" alt="thumbs">
									</li>
								</ul>
								<a href="#" class="btn btn-sm btn-block btn-default">Browse Gallery</a>
							</div>
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

