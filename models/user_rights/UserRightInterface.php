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
 * @property-read bool $hidden
 */
interface UserRightInterface {
	/*Константы доступа*/
	public const ACCESS_DENY = false;
	public const ACCESS_ALLOW = true;
	public const ACCESS_UNDEFINED = null;

	/**
	 * Магическое свойство, необходимое для сравнения классов, например
	 * Предполагается, что будет использоваться имя класса
	 * @return string
	 */
	public function __toString():string;

	/**
	 * Уникальный идентификатор (подразумевается имя класса)
	 * @return string
	 */
	public function getId():string;

	/**
	 * Вернуть true, если правило не должно быть доступно в выбиралке
	 * @return bool
	 */
	public function getHidden():bool;

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
	public function getAccess(string $controller, string $action, array $actionParams = []):?bool;//todo static

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array;

}