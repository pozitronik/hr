<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use yii\web\View; ?>

<?= $this->render('@app/modules/users/views/users/index', [
	'searchModel' => null,
	'dataProvider' => $dataProvider
]) ?>
