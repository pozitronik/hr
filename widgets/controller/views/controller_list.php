<?php
declare(strict_types = 1);

/* @var View $this
 * @var WigetableController $model
 * @var array $action
 * @var false|string $caption
 */

use app\models\core\WigetableController;
use yii\web\View;
use yii\helpers\Html;

?>

<p class="text-semibold text-dark mar-no"><?= Html::a($caption, $action) ?></p>



