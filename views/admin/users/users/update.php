<?php

/**
 * @var Users $model
 */

use app\models\users\Users;

$this->title = 'Редактирование пользователя';

?>

<?= $this->render('_form', [
	'model' => $model,
]);
?>