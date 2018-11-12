<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\base\Model;

/**
 * Class Bookmarks
 * Обращение к закладкам, хранящимся в sys_user_options::bookmarks
 * @package app\models\users
 * @property string $route
 * @property string $name
 * @property integer $type
 * @property integer $order
 */
class Bookmarks extends Model {

	public const TYPE_DEFAUT = 0;
	public const TYPE_IMPORTANT = 1;
	public const TYPE_URGENT = 2;
	public const TYPE_WARNING = 3;


}