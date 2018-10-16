<?php
declare(strict_types = 1);

/* @var View $this
 * @var WigetableController $model
 */

use app\models\core\Magic;
use app\models\core\WigetableController;
use yii\web\View;
use yii\helpers\Url;

$style = Magic::hasProperty($model, 'menuIcon')?"style = 'background-image: url({$model->menuIcon});'":'';
$action = ["{$model->route}/{$model->defaultAction}"];
$caption = Magic::hasProperty($model, 'menuCaption')?$model->menuCaption:$model->id;
?>

<a href="<?= Url::toRoute($action); ?>" class="icon">
	<div class="panel panel-icon">
		<div class="panel-body">
			<div class="text-center bord-btm">
				<div class="menu-widget-icon" <?= $style ?> ></div>
			</div>
		</div>
		<!--				<div class="text-sm-center"><?= $caption ?><</div>-->
	</div>

</a>


