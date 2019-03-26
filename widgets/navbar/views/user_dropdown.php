<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\modules\users\models\Users;
use yii\helpers\Html;
use yii\web\View;

?>

<li id="dropdown-user" class="dropdown">
	<a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
		<div class="username hidden-xs"><?= $user->username ?></div>
		<span class="pull-right">
			<img class="img-circle img-user media-object img-border-current" src="<?= $user->avatar ?>" alt="Profile Picture">
			<i class="demo-pli-male ic-user"></i>
		</span>
	</a>


	<div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">

		<!-- Dropdown heading  -->
		<div class="pad-all bord-btm">
			<?= $user->comment ?>
		</div>


		<!-- User dropdown menu -->
		<ul class="head-list">
			<li>
				<?= Html::a("Профиль", ["/users/users/profile", "id" => $user->id]) ?>
			</li>
		</ul>

		<!-- Dropdown footer -->
		<div class="pad-all text-right">
			<?= Html::a("Выйти", ['/site/logout'], ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
</li>