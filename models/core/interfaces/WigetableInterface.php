<?php
declare(strict_types = 1);

/**
 * Interface WigetableInterface
 * Расширения контроллера, позволяющие добавлять его в меню
 */
interface WigetableInterface {

	/**
	 * Возвращает название пуннкта меню, добавляемого контроллером
	 * @return string
	 */
	public function getMenuCaption(): string;

	/**
	 * Возвращает путь к иконке пункта меню
	 * @return string
	 */
	public function getControllerIcon(): string;

}