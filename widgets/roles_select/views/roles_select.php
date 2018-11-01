<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $data
 * @var array $value
 * @var integer $groupId
 * @var integer $userId
 */

use kartik\select2\Select2;
use yii\web\View;
use kartik\spinner\Spinner;

?>
<?= Select2::widget([
	'data' => $data,
	'name' => "UserRoles[$userId]",
	'value' => $value,
	'options' => [
		'placeholder' => 'Укажите роль в группе'
	],
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => true
	],
	'pluginEvents' => [
		"change.select2" => "function(e) {set_roles($userId, $groupId, jQuery(e.target).val())}"
	],
	'addon' => [
		'append' => [
			'content' => Spinner::widget(['preset' => 'small', 'align' => 'right', 'hidden' => true, 'id' => "{$userId}-roles-progress"]),
			'asButton' => false
		]
	]

]);

?>