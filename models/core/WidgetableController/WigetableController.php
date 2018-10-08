<?php
declare(strict_types = 1);

use yii\web\Controller;

/**
 * Class WigetableController
 * Расширенный класс контроллера с дополнительными опциями встройки в меню и навигацию
 *
 * @property-read false|string $menuIcon
 * @property-read false|string $menuCaption
 */
class WigetableController extends Controller {

	/**
	 * Возвращает путь к иконке контроллера
	 * @return false|string
	 */
	public function getMenuIcon() {
		return false;
	}

	/**
	 * Возвращает строковое название пункта меню контроллера
	 * @return false|string
	 */
	public function getMenuCaption() {
		return false;
	}
}