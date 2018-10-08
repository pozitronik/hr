<?php
declare(strict_types = 1);

/* @var View $this
 * @var WigetableController $model
 */

use app\models\core\Magic;
use app\models\core\WigetableController;
use yii\web\View;
use yii\helpers\Url;

$icon_url = Magic::hasProperty($model, 'menuIcon')?$model->menuIcon:"/img/admin/widget.png";//todo
$action = ["{$model->route}/{$model->defaultAction}"];
$caption = Magic::hasProperty($model, 'menuCaption')?$model->menuCaption:$model->id;
?>

<a href="<?= Url::toRoute($action); ?>" class="icon">
	<div class="fixed-fluid">
		<div class="pull-sm-left">
			<div class="panel panel-icon">
				<div class="panel-body">
					<div class="text-center bord-btm">
						<img src="<?= $icon_url ?>">
					</div>
				</div>
				<div class="text-sm-center"><?= $caption ?></div>
			</div>

		</div>
	</div>
</a>


