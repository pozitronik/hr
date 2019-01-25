<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int $step
 * @var int $domain
 */

use app\modules\import\models\fos\ImportFosDecomposed;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?= ImportFosDecomposed::step_labels[$step]; ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				Импорт разбит на шаги из-за продолжительности, ошибки пока не анализируются
			</div>
		</div>

	</div>
	<div class="panel-footer">
		<?= Html::a('Повторить', ['import', 'step' => $step, 'domain' => $domain], ['class' => 'btn btn-warning pull-left']); ?>
		<?= ($step !== ImportFosDecomposed::LAST_STEP)?Html::a('Следующий шаг', ['import', 'step' => $step + 1, 'domain' => $domain], ['class' => 'btn btn-success pull-right']):Html::a('Готово, домой', ['/home/index', 'domain' => $domain], ['class' => 'btn btn-success pull-right']); ?>
		<div class="clearfix"></div>
	</div>
</div>


