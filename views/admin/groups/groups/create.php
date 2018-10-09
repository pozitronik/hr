<?php
declare(strict_types = 1);

/**
 * @var Groups $model
 * @var View $this
 */

use app\models\groups\Groups;
use yii\web\View;


?>

<?= $this->render('_form', [
	'model' => $model
]);
?>