<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\components\pozitronik\core\models\core_module\PluginsSupport;
use app\modules\home\HomeModule;
use yii\base\View;
use yii\helpers\Html;
use app\modules\users\widgets\bookmarks\BookmarksWidget;
use app\widgets\admin_panel\AdminPanelWidget;
use app\modules\users\models\Users;

?>

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
					<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no">Дашборд</p><small class="text-muted">Дашборд всех моих групп</small></div>', HomeModule::to("home")) ?></li>
					<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no">Атрибуты</p><small class="text-muted">Поиск сотрудников по атрибутам</small></div>', ["/attributes/attributes/search"]) ?></li>
					<?php if (false /*&& UserAccess::GetFlag(ServiceAccess::FLAG_SERVICE)*/): ?>
						<li><?= Html::a('<div class="media-body"><p class="text-semibold text-dark mar-no"><i class="fa fa-radiation-alt"></i> Ядерный пепел <i class="fa fa-radiation-alt"></i></p><small class="text-muted">Сброс сервиса в исходное состояние</small></div>', ["/service/service/index"]) ?></li>
					<?php endif; ?>
				</ul>

			</div>
			<div class="col-sm-4 col-md-3">
				<?= BookmarksWidget::widget() ?>
			</div>
			<?php if ($user->is('admin')): ?>
				<?= AdminPanelWidget::widget([
					'mode' => AdminPanelWidget::MODE_LIST,
					'controllers_directory' => array_merge([AdminPanelWidget::DEFAULT_DIRECTORY], PluginsSupport::GetAllControllersPaths())
				]) ?>
			<?php endif; ?>
		</div>
	</div>
</li>