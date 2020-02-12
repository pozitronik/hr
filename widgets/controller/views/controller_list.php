<?php
declare(strict_types = 1);

/* @var View $this
 * @var WigetableController $model
 * @var array $action
 * @var false|string $caption
 */

use pozitronik\core\models\core_controller\WigetableController;
use yii\web\View;
use yii\helpers\Html;

?>

<p class="text-semibold text-dark mar-no"><?= Html::a($caption, $action) ?></p>



