<?php
declare(strict_types = 1);
/**
 * @var View $this
 * @var Reference $model
 */

use app\models\references\Reference;
use yii\web\View;

echo $this->render($model->form, [
	'model' => $model
]);