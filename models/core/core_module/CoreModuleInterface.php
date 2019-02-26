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
	 * Возвращает название плагина
	 * @return string
	 */
	public function getName():string;

	/**
	 * Возвращает неймспейс загруженного модуля (для вычисления алиасных путей внутри модуля)
	 * @return string
	 */
	public function getNamespace():string;

	/**
	 * Возвращает зарегистрированный алиас модуля
	 * @return string
	 */
	public function getAlias():string;

}