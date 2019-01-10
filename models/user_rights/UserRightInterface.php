<?php
declare(strict_types = 1);

namespace app\models\user_rights;

/**
 * Interface UserRight
 * @package app\models\user_rights
 * Интерфейс права пользователя.
 * Каждое право определяет ту или иную возможность действия.
 * Набор прав объединяется под общим алиасом (привилегией), определённым в классе Privileges
 * @property-read string $id
 * @property-read string $name
 * @property-read string $description
 */
interface UserRightInterface {
	/*Константы доступа*/
	public const ACCESS_DENY = false;
	public const ACCESS_ALLOW = true;
	public const ACCESS_UNDEFINED = null;

	/**
	 * Уникальный идентификатор (подразумевается имя класса)
	 * @return string
	 */
	public function getId():string;

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
	 * @param string $controller Имя класса контроллера
	 * @param string $action Имя экшена
	 * @return bool|null Одна из констант доступа
	 */
	public function getAccess(string $controller, string $action):?bool;

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array;

}