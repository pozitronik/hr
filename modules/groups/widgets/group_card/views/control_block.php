<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $targetId
 * @var bool $expanded -- initial state
 **/

use app\helpers\IconsHelper;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\web\View;

$this->registerJs("$('.panel-card, .panel-card-small').on('hidden.bs.collapse', function(){
    if ('undefined' !== typeof (Msnry)) Msnry.layout();
  });");
$this->registerJs("$('.panel-card, .panel-card-small').on('shown.bs.collapse', function(){
   if ('undefined' !== typeof (Msnry)) Msnry.layout();
  });");
?>
<?= Html::button($expanded?IconsHelper::minimize():IconsHelper::maximize(), [
	'class' => 'btn btn-default',
	'data-toggle' => "collapse",
	'data-target' => "#{$targetId}",
	'aria-expanded' => $expanded?'true':'false',
	'onClick' => new JsExpression("changeIcon($(this));")
]) ?>

