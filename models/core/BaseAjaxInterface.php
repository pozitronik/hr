<?php
declare(strict_types = 1);

namespace app\models\core;

/**
 * Объявления всяких аякосвых сущностей-общностей
 * Class Ajax
 * @package app\models\core
 */
interface BaseAjaxInterface {

	public const RESULT_OK = 0;/*Отработано*/
	public const RESULT_ERROR = 1;/*Ошибка*/
	public const RESULT_POSTPONED = 2;/*На будущее*/

}