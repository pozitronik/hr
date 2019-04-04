<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class TimelineEntry
 * @package app\models\prototypes
 *
 * @property string $icon
 * @property string $time
 * @property string $header
 * @property string $content
 */
class TimelineEntry extends Model {
	public $icon;
	public $time;
	public $header;
	public $content;
}