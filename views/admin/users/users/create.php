<?php

/**
 * @var Users $model
 */

use app\models\users\Users;

$this->title = 'Добавление пользователя';

?>

<?= $this->render('_form', [
	'model' => $model,
]);
?>