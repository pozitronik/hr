<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var int $domain
 * @var array $messages
 */

use pozitronik\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;

?>

	<div class="panel">
		<div class="panel-heading">
			<h3 class="panel-title">Готово!</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?php if ([] === $messages): ?>
						<?= Html::label('Нет ошибок') ?>
					<?php else: ?>
						<?= Html::label('Ошибки:') ?>
						<br/>
						<?php Utils::log($messages); ?>
					<?php endif; ?>
				</div>
			</div>

		</div>
		<div class="panel-footer">
			<?= Html::a('Повторить', ['import'], ['class' => 'btn btn-warning pull-left']) ?>
			<?= Html::a('К началу', 'index', ['class' => 'btn btn-default pull-right']) ?>
			<div class="clearfix"></div>
		</div>
	</div>
