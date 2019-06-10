<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class PrototypeNodeData
 *
 * @property integer $nodeId
 * @property integer $x
 * @property integer $y
 */
class PrototypeNodeData extends Model {
	public $nodeId;
	public $x;
	public $y;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['nodeId'], 'integer'],
			[['x', 'y'], 'number'],
			[['nodeId', 'x', 'y'], 'required']
		];
	}
}