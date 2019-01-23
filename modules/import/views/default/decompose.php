<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int $step
 * @var int $domain
 * @var array $messages
 */

use app\helpers\Utils;
use app\modules\import\models\ImportFos;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?= ImportFos::step_labels[$step]; ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?php if ([] === $messages): ?>
					<?= Html::label('Нет ошибок'); ?>
				<?php else: ?>
					<?= Html::label('Ошибки:'); ?>
					<br/>
					<?php Utils::log($messages); ?>
				<?php endif; ?>
			</div>
		</div>

	</div>
	<div class="panel-footer">
		<?php if ([] === $messages): ?>
			<?= Html::a('Повторить', ['decompose', 'step' => $step, 'domain' => $domain], ['class' => 'btn btn-warning pull-left']); ?>
			<?= (ImportFos::STEP_FINISH !== $step)?Html::a('Следующий шаг', ['decompose', 'step' => $step + 1, 'domain' => $domain], ['class' => 'btn btn-success pull-right']):Html::a('К результату', ['result', 'domain' => $domain], ['class' => 'btn btn-success pull-right']); ?>
		<?php else: ?>
			<?= Html::a('Повторить', ['decompose', 'step' => $step, 'domain' => $domain], ['class' => 'btn btn-warning pull-left']); ?>
			<?= Html::a('К началу', 'import', ['class' => 'btn btn-default pull-left']); ?>
		<?php endif; ?>
		<div class="clearfix"></div>
	</div>
</div>


