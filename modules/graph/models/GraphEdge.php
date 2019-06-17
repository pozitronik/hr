<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use yii\base\ArrayableTrait;
use yii\base\Model;

/**
 * Class GraphEdge
 * @package app\models\prototypes
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $label
 * @property string $color
 */
class GraphEdge extends Model {
	use ArrayableTrait;

	public $id;
	public $from;
	public $to;
	public $label;
	public $color;

}