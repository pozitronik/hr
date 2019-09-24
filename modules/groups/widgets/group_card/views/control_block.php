<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $target
 * @var bool $expanded
 **/

use app\helpers\IconsHelper;
use yii\bootstrap\Html;
use yii\web\View;

?>
<?= Html::button(IconsHelper::maximize(), ['class' => 'btn btn-default', 'data-toggle' => "collapse", 'data-target' => "#{$target}", "aria-expanded" => 'false']); ?>
