<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use yii\db\ActiveRecord;
use yii\web\View;


?>

<?= $this->render('_form', [
	'model' => $model
]);
?>