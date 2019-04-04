<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Модель истории изменений объекта (предполагается, что это ActiveRecord, но по факту это любая модель с атрибутами)
 *
 * @property ActiveRecord $loggerModel AR-интерфейс для работы с базой логов
 * @property ActiveRecord $requestModel Модель, для которой запрашиваем историю
 */
class ModelHistory extends Model {
	public $loggerModel;
	public $requestModel;

	/**
	 * @return ActiveRecord[]
	 * @throws InvalidConfigException
	 */
	public function getHistory():array {
		$formName = $this->requestModel->formName();
		$modelKey = $this->requestModel->primaryKey;

		return $this->loggerModel::find()->where(['model' => $formName, 'modelKey' => $modelKey])->all();
	}

}