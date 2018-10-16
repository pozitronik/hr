<?php
declare(strict_types = 1);

use yii\web\View;
use app\models\references\Reference;

/**
 * @var View $this
 * @var Reference $model
 */

echo $this->render($model->form, [
	'model' => $model
]);