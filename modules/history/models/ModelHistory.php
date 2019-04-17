<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\models\core\helpers\ReflectionHelper;
use app\models\core\LCQuery;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;

/**
 * Модель истории изменений объекта (предполагается, что это ActiveRecord, но по факту это любая модель с атрибутами)
 *
 * @property ActiveRecordLoggerInterface $loggerModel AR-интерфейс для работы с базой логов
 */
class ModelHistory extends Model {
	public $loggerModel;

	private $requestModel;
	private $modelHistoryRules = [];

	/**
	 * @param string $className
	 * @param int $modelKey
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public function getHistory(string $className, int $modelKey):array {
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;
		if (null === $askedClass = ReflectionHelper::LoadClassByName(self::ExpandClassName($className), null, false)) {//не получилось сопоставить класс модели, грузим as is
			return $this->loggerModel::find()->where(['model' => $className, 'model_key' => $modelKey])->orderBy('at')->all();
		}
		$this->requestModel = $askedClass::findModel($modelKey);
		$this->modelHistoryRules = $this->requestModel->hasMethod('historyRules')?$this->requestModel->historyRules():[];

		/** @var LCQuery $findCondition */
		$findCondition = $this->loggerModel::find()->where(['model' => $this->requestModel->formName(), 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели
		/** @var array $relationsRules */
		$relationsRules = ArrayHelper::getValue($this->modelHistoryRules, 'relations', []);
		foreach ($relationsRules as $relatedModelClassName => $relationRule) {/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в связанных таблицах*/
			$relatedModel = ReflectionHelper::LoadClassByName($relatedModelClassName);
			if (is_callable($relationRule)) {
				$relationRule($findCondition, $relatedModel);
			} elseif (is_array($relationRule)) {
				$linkKey = ArrayHelper::key($relationRule);
				$linkValue = $relationRule[$linkKey];
				$modelKey = $this->requestModel->$linkKey;
				$findCondition->orWhere("model = '{$relatedModelClassName}' and (new_attributes->'$.{$linkValue}' = {$modelKey} or old_attributes->'$.{$linkValue}' = {$modelKey})");
			} else throw new InvalidConfigException('Relation rule must be array or callable instance!');
		}
		return $findCondition->orderBy('at')->all();
	}

	/**
	 * @param string $shortClassName
	 * @return string
	 * @throws Throwable
	 */
	public static function ExpandClassName(string $shortClassName):string {
		return ArrayHelper::getValue(Yii::$app->modules, "history.params.classNamesMap.$shortClassName", $shortClassName);
	}

}