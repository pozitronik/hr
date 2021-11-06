<?php
declare(strict_types = 1);

namespace app\modules\graph\models;

use Exception;
use yii\base\ArrayableTrait;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class GraphNode
 * @package app\models\prototypes
 *
 * @property int $x
 * @property int $y
 * @property string $id
 * @property string $label
 * @property int $size
 * @property string $color
 * @property string $shape
 * @property string $image
 * @property boolean $widthConstraint
 */
class GraphNode extends Model {
	use ArrayableTrait;

	public $x = 0;
	public $y = 0;
	public $id;
	public $label;
	public $shape;
	public $image;
	public $color;
	public $widthConstraint;

	/**
	 * GraphNode constructor.
	 * @param Model $model
	 * @param array $config
	 * @throws InvalidConfigException
	 */
	public function __construct(Model $model, array $config = []) {
		parent::__construct($config);
		$this->id = $model->formName();
	}


	/**
	 * @return string
	 * @throws Exception
	 */
	protected function getRandomRGB():string {
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
		return "rgb({$red},{$green},{$blue})";
	}
}