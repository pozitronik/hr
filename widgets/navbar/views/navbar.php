<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\modules\users\models\Users;
use app\widgets\search\SearchWidget;

use yii\web\View;
?>

<header id="navbar">
	<div id="navbar-container" class="boxed">
		<!--Brand logo & name-->
		<!--================================-->
		<div class="navbar-header">
			<ul class="nav navbar-top-links pull-left">
				<!--Mega dropdown-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<?= $this->render('dropdown', [
					'user' => $user
				]) ?>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End mega dropdown-->

			</ul>
		</div>
		<!--================================-->
		<!--End brand logo & name-->

		<!--Navbar Dropdown-->
		<!--================================-->
		<div class="navbar-content clearfix">
			<?= $this->render('breadcrumbs') ?>

			<ul class="nav navbar-top-links pull-right">
				<?= SearchWidget::widget() ?>
				<?= $this->render('user_dropdown', [
					'user' => $user
				]) ?>

			</ul>
		</div>
		<!--================================-->
		<!--End Navbar Dropdown-->

	</div>
</header>

