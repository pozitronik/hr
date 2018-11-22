<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;

/**
 * Class PrototypeCompetencySearchCondition
 * Модель условий поиска по компетенциям
 * @package app\models\prototypes
 */
class PrototypeCompetencySearchCondition extends Model {

	public static $conditions = [
		'string' => [
			'равно' => '',
			'не равно' => '',
			'начинается с' => '',
			'содержит' => '',
			'не содержит' => '',
			'заполнено' => '',
			'не заполнено' => ''
		],
		'integer' => [
			'равно' => '',
			'не равно' => '',
			'больше' => '',
			'меньше' => '',
			'больше или равно' => '',
			'меньше или равно' => '',
			'заполнено' => '',
			'не заполнено' => ''
		],
		'boolean' => [
			'да' => '',
			'нет' => '',
			'не заполнено' => ''
		],
		'date' => [
			'равно' => '',
			'не равно' => '',
			'раньше' => '',
			'позже' => '',
			'раньше или равно' => '',
			'позже или равно' => '',
			'заполнено' => '',
			'не заполнено' => ''
		],
		'time' => [
			'равно' => '',
			'не равно' => '',
			'раньше' => '',
			'позже' => '',
			'раньше или равно' => '',
			'позже или равно' => '',
			'заполнено' => '',
			'не заполнено' => ''
		],
		'percent' => [
			'равно' => '',
			'не равно' => '',
			'больше' => '',
			'меньше' => '',
			'больше или равно' => '',
			'меньше или равно' => '',
			'заполнено' => '',
			'не заполнено' => ''
		]
	];

	/**
	 * @param string $type
	 * @return array
	 * @throws Throwable
	 */
	public static function findCondition(string $type):array {
		return ArrayHelper::getValue(self::$conditions, $type, []);
	}
}