<?php

/**
 * @var Users $model
 * @var boolean $success //не нужно, можно проверять по модели, но пусть будет в прототипе
 */

use app\models\users\Users;

$this->title = 'Добавление пользователя';

?>

<?= $this->render('_form', [
	'model' => $model,
	'success' => $success
]);
?>