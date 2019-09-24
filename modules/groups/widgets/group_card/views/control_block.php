<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $target
 * @var bool $expanded -- initial state
 **/

use app\helpers\IconsHelper;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\web\View;

?>
<?= Html::button($expanded?IconsHelper::minimize():IconsHelper::maximize(), [
	'class' => 'btn btn-default',
	'data-toggle' => "collapse",
	'data-target' => "#{$target}",
	'aria-expanded' => $expanded?'true':'false',
	'onClick' => new JsExpression("changeIcon($(this))")
]) ?>
