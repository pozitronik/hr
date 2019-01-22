<?php
declare(strict_types = 1);

/**
 * @var int|null $domain
 * @var View $this
 */

use yii\bootstrap\Button;
use yii\web\View;
use yii\helpers\Html;

?>

<?= Html::a(Button::widget([
	'label' => '<i class="fa fa-thumbs-up"></i> Готово <i class="fa fa-thumbs-up"></i>',
	'encodeLabel' => false,
	'options' => ['class' => 'btn-lg btn btn-danger']
]), Yii::$app->homeUrl);
?>
