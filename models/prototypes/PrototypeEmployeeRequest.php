<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class PrototypeEmployeeRequest
 * @package app\models\prototypes
 * @property string $requestText
 */
class PrototypeEmployeeRequest extends Model {

	public $requestText;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['requestText'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'requestText' => 'Обоснование запроса в свободной форме'
		];
	}
}