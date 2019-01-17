<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int $step
 * @var int $domain
 * @var $messages array
 */

use app\helpers\Utils;
use yii\helpers\Html;
use yii\web\View;

?>

<?php if ([] === $messages): ?>
	<?= Html::label('Нет ошибок'); ?>
	<br/>
	<?= Html::a('Повторить', ['decompose', 'step' => $step, 'domain' => $domain], ['class' => 'btn btn-warning pull-right']); ?>
	<?= (3 !== $step)?Html::a('Следующий шаг', ['decompose', 'step' => $step + 1, 'domain' => $domain], ['class' => 'btn btn-default']):Html::a('К результату', ['result', 'domain' => $domain], ['class' => 'btn btn-success']); ?>
<?php else: ?>
	<?= Html::label('Ошибки:'); ?>
	<br/>
	<?php Utils::log($messages); ?>
	<?= Html::a('Повторить', ['decompose', 'step' => $step, 'domain' => $domain], ['class' => 'btn btn-warning pull-right']); ?>
	<?= Html::a('К началу', 'import', ['class' => 'btn btn-default pull-left']); ?>
<?php endif; ?>


