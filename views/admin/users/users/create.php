<?php
declare(strict_types=1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\users\Users;
use yii\web\View;

$this->title = 'Добавление пользователя';

?>

<?= $this->render('_form', [
	'model' => $model
]);
?>