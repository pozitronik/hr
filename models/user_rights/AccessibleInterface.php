<?php
declare(strict_types = 1);

namespace app\models\user_rights;

/**
 * Interface AccessibleInterface
 * @package app\models\user_rights
 * @todo implement on all internal models
 */
interface AccessibleInterface {

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function createModel(array $paramsArray):bool;

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function updateModel(array $paramsArray):bool;


}