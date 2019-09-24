<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $target
 **/

use app\helpers\IconsHelper;
use yii\bootstrap\Html;
use yii\web\View;

?>
<?= Html::button(IconsHelper::maximize(), ['class' => 'btn btn-default', 'data-target' => "#{$target}}"]); ?>
<?= Html::button(IconsHelper::expand(), ['class' => 'btn btn-default', 'data-toggle' => "panel-overlay", 'data-target' => "#{$target}}"]); ?>
