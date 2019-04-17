<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordExtended;
use app\models\core\helpers\ReflectionHelper;
use app\models\core\LCQuery;
use app\modules\users\models\Users;
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

		try {
			$askedClass = ReflectionHelper::LoadClassByName(self::ExpandClassName($className));//Пытаемся загрузить класс приложения
			$this->requestModel = $askedClass::findModel($modelKey);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (ReflectionException $t) {//не получилось загрузить, грузим as is
			return $this->loggerModel::find()->where(['model' => $className, 'model_key' => $modelKey])->orderBy('at')->all();
		}

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
				$findCondition->orWhere("model = '{$relatedModel->formName()}' and (new_attributes->'$.{$linkValue}' = {$modelKey} or old_attributes->'$.{$linkValue}' = {$modelKey})");
			} else throw new InvalidConfigException('Relation rule must be array or callable instance!');
		}
		return $findCondition->orderBy('at')->all();
	}

	/**
	 * @param string $attributeName Название атрибута, для которого пытаемся найти подстановку
	 * @param mixed $attributeValue Значение атрибута, которому ищем соответствие
	 * @param string $substitutionClassName Имя AR-класса, по записям которого будем искать соответствие
	 * @return mixed Подстановленное значение (если найдено, иначе переданное значение)
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function SubstituteAttributeValue(string $attributeName, $attributeValue, string $substitutionClassName) {
		$relationModel = ReflectionHelper::LoadClassByName(self::ExpandClassName($substitutionClassName));

		if (null === $attributeConfig = ArrayHelper::getValue($relationModel->historyRules(), "attributes.{$attributeName}")) return $attributeValue;
		if (false === $attributeConfig) return false;//не показывать атрибут
		if (is_callable($attributeConfig)) {
			return $attributeConfig($attributeName, $attributeValue);
		}
		if (is_array($attributeConfig)) {//[className => valueAttribute]
			$fromModelName = ArrayHelper::key($attributeConfig);
			/** @var ActiveRecordExtended $fromModel */
			$fromModel = ReflectionHelper::LoadClassByName($fromModelName);
			$modelValueAttribute = $attributeConfig[$fromModelName];
			return ArrayHelper::getValue($fromModel::findModel($attributeValue), $modelValueAttribute, $attributeValue);
		} else return $attributeConfig;//Можем вернуть прямо заданное значение
	}

	/**
	 * Переводит набор записей из лога в набор событий
	 * @param ActiveRecordLoggerInterface[] $timeline
	 * @return HistoryEventInterface[]
	 * @throws Throwable
	 */
	public function populateTimeline(array $timeline):array {
		$result = [];
		foreach ($timeline as $record) {
			$result[] = $this->getHistoryEvent($record);
		}
		return $result;
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