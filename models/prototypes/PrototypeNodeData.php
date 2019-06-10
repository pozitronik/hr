<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class PrototypeNodeData
 *
 * @property string $name -- configuration name
 * @property integer $groupId
 * @property integer $nodeId
 * @property integer $x
 * @property integer $y
 */
class PrototypeNodeData extends Model {

	public $name;
	public $groupId;
	public $nodeId;
	public $x;
	public $y;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['name'], 'string'],
			[['groupId', 'nodeId', 'userId'], 'integer'],
			[['x', 'y'], 'number'],
			[['groupId', 'nodeId', 'x', 'y'], 'required']
		];

	}
}