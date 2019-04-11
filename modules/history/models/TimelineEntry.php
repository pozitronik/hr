<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\modules\users\models\Users;
use yii\base\Model;

/**
 * Class TimelineEntry
 *
 * @property string $icon
 * @property string $time
 * @property string $caption
 * @property string $content
 * @property Users $user
 */
class TimelineEntry extends Model {
	public $icon;
	public $time;
	public $caption;
	public $content;
	public $user;
}