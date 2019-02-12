<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

/**
 * Интерфейс системного модуля приложения
 * Interface CoreModule
 * @package app\models\core
 */
interface CoreModuleInterface {
	/**
	 * Функция должна вернуть корневой путь модуля (ровно тот же, что указан в web.php)
	 * @return string
	 */
	public static function Root():string;

	/**
	 * Функция, возвращающая путь внутри модуля (в формате Url::to())
	 * @param string|null $controller - контроллер модуля (если не указан, то используется контроллер, указанный в $defaultRoute)
	 * @param string|null $action - экшен модуля (если не указан, то используется дефолтный экшен контроллера)
	 * @param array $parameters - параметры, передаваемые в экшен
	 * @return array
	 */
	public function getRoute(?string $controller = null, ?string $action = null, array $parameters = []):array;

}