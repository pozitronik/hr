<?php
declare(strict_types = 1);

/**
 * @var Groups $model
 * @var View $this
 */

use yii\web\View;
use app\models\groups\Groups;

?>

<?= $this->render('_form', [
	'model' => $model
]);
?>