<?php
declare(strict_types = 1);

namespace app\models\user_rights;


/**
 * Class AccessMethods
 * @package app\models\user_rights
 */
abstract class AccessMethods {
	public const __default = self::any;

	const any = null;
	const view = 0;
	const create = 1;
	const update = 2;
	const delete = 3;
	/*et cetera*/
//	const take_ownership = 300;
//	const prune = 100;

}