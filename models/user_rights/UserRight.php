<?php
declare(strict_types = 1);

namespace app\models\user_rights;

/**
 * Interface UserRight
 * @package app\models\user_rights
 * Интерфейс права пользователя.
 * Каждое право определяет ту или иную возможность действия.
 * Набор прав объединяется под общим алиасом (RightsSet)
 */
interface UserRight {

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string;

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string;

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array;

}