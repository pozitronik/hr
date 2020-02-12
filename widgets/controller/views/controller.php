<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController $model
 * @var false|string $caption
 * @var string $style
 * @var array $action
 */

use pozitronik\core\models\core_controller\WigetableController;
use yii\web\View;
use yii\helpers\Url;

?>

<a href="<?= Url::toRoute($action) ?>" class="icon">
	<div class="panel panel-icon">
		<div class="panel-body">
			<div class="text-center bord-btm">
				<div class="menu-widget-icon" <?= $style ?> ></div>
			</div>
		</div>
		<!--				<div class="text-sm-center"><?= $caption ?><</div>-->
	</div>

</a>


