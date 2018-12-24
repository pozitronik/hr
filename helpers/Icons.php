<?php
declare(strict_types = 1);

namespace app\helpers;

/**
 * Class Icons
 * @package app\helpers
 * Хелпер с HTML-иконками
 */
class Icons {

	/**
	 * @return string
	 */
	public static function trash():string {
		return "<i class='fa fa-trash'></i>";
	}
}