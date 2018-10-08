<?php
declare(strict_types = 1);

/* @var View $this
 * @var Controller $model
 */

use app\models\core\Magic;
use yii\web\Controller;
use yii\web\View;
use yii\helpers\Html;

$icon_url = Magic::hasProperty($model,'menuIcon')?$model->menuIcon:"/img/admin/widget.png";
$action = ["{$model->route}/{$model->defaultAction}"];
$caption = Magic::hasProperty($model,'menuCaption')?$model->menuCaption:$model->id;
?>
<?= Html::a('<div class="fixed-fluid">
	<div class="fixed-sm-200 fixed-lg-200 pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<img src="'.$icon_url.'" class="img-lg img-border img-circle">
				</div>
			</div>
			<div class="text-sm-center">'.$caption.'</div>
		</div>
	</div>
</div>', $action);

?>
