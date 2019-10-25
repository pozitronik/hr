<?php
declare(strict_types = 1);

/** @var View $this
 */

use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\web\View;

?>

<?= Html::a(Button::widget([
	'label' => '<i class="fa fa-radiation"></i> Очистка базы <i class="fa fa-radiation"></i>',
	'encodeLabel' => false,
	'options' => ['class' => 'btn-lg btn btn-danger']
]), ['service/service/reset'])
?>
<?= Html::a(Button::widget([
	'label' => '<i class="fa fa-radiation"></i> Плеснуть мутаген <i class="fa fa-radiation"></i>',
	'encodeLabel' => false,
	'options' => ['class' => 'btn-lg btn btn-danger']
]), ['service/service/mask-and-shit'])
?>