<?php
declare(strict_types = 1);

 namespace app\models\core;

 /**
  * Интерфейс, строго описывающий общие моменты во внутренних моделях (чтобы не сбиться)
  * Interface StrictInterface
  * @package app\models\core
  */
 interface StrictInterface {

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function createModel(?array $paramsArray):bool;

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function updateModel(?array $paramsArray):bool;


}