<?php /** @noinspection ALL */
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordExtended;
use app\models\core\helpers\ReflectionHelper;
use app\models\core\LCQuery;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use ReflectionClass;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordLogger
 * @property integer $id
 * @property-read string $at
 * @property-read string $timestamp//alias of $at
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 * @property-read int $eventType
 *
 * @property-read ActiveQuery|Users $relUser
 * @property-read HistoryEventInterface $event
 * @property-read HistoryEventAction[] $eventActions
 *
 * @property object|ReflectionClass|null $loadedModel Прогруженная (если есть возможность) модель указанного в логе класса
 */
class ActiveRecordLogger extends ActiveRecord implements ActiveRecordLoggerInterface {

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'sys_log';
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'at' => 'Время события',
			'timestamp' => 'Время события',
			'user' => 'Пользователь',
			'model' => 'Источник',
			'old_attributes' => 'Было',
			'new_attributes' => 'Стало',
			'eventType' => 'Тип события',
			'relUser' => 'Пользователь',
			'username' => 'Пользователь',
			'actions' => 'События'
		];
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['user', 'model_key'], 'integer'],
			[['model'], 'string'],
			[['old_attributes', 'new_attributes'], 'safe']
		];
	}

	/**
	 * @param ActiveRecord $model
	 * @param bool $ignoreUnchanged
	 * @return bool
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function logChanges(ActiveRecord $model, bool $ignoreUnchanged = true):bool {
		if (ArrayHelper::getValue($model, 'loggingEnabled', false)) {
			if (([] === $diff = $model->identifyChangedAttributes()) && $ignoreUnchanged) return true;
			$changedAttributes = array_intersect_key($model->oldAttributes, $diff);
			self::push($model->formName(), $model->primaryKey, $changedAttributes, $diff);
		}
		return true;
	}

	/**
	 * @param ActiveRecord $model
	 * @throws InvalidConfigException
	 */
	public static function logModel(ActiveRecord $model):void {
		self::push($model->formName(), $model->primaryKey, [], $model->attributes);
	}

	/**
	 * Логирует удаление модели
	 * @param ActiveRecord $model
	 * @throws InvalidConfigException
	 */
	public static function logDelete(ActiveRecord $model):void {
		self::push($model->formName(), $model->primaryKey, $model->attributes, []);
	}

	/**
	 * @param string|null $modelName
	 * @param mixed $pKey
	 * @param array $oldAttributes
	 * @param array $newAttributes
	 */
	private static function push(?string $modelName, $pKey, array $oldAttributes, array $newAttributes):void {
		$pKey = is_numeric($pKey)?$pKey:null;//$pKey может быть массивом

		$log = new self([
			'user' => CurrentUser::Id(),
			'model' => $modelName,
			'model_key' => $pKey,
			'old_attributes' => $oldAttributes,
			'new_attributes' => $newAttributes
		]);
		$log->save();
	}

	/**
	 * @return string
	 */
	public function getTimestamp():string {
		return $this->at;
	}

	/**
	 * @return object|ReflectionClass|null
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public function getLoadedModel() {
		return ReflectionHelper::LoadClassByName(self::ExpandClassName($this->model), null, false);
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 * @throws Throwable
	 */
	private function getModelRules(string $key) {
		if (null === $this->loadedModel) return null;
		return ArrayHelper::getValue($this->loadedModel->historyRules(), $key);
	}

	/**
	 * @return int
	 * @throws Throwable
	 */
	public function getEventType():int {
		if (null !== $eventsConfig = $this->getModelRules("events")) {
			/** @var array $eventRule */
			foreach ($eventsConfig as $eventType => $eventRule) {
				foreach ($eventRule as $attribute => $condition) {
					if (is_array($condition)) {
						$oldAssumedValue = ArrayHelper::getValue($condition, 'from');
						$newAssumedValue = ArrayHelper::getValue($condition, 'to');
						if (null !== $oldAssumedValue) {
							$fromCondition = $oldAssumedValue == ArrayHelper::getValue($this->old_attributes, $attribute);//не используем строгое сравнение
						} else $fromCondition = true;

						if (null !== $newAssumedValue) {
							$toCondition = $newAssumedValue == ArrayHelper::getValue($this->new_attributes, $attribute);
						} else $toCondition = true;

						if ($fromCondition && $toCondition) return $eventType;
					} else if ($condition == ArrayHelper::getValue($this->new_attributes, $attribute)) return $eventType;
				}
			}
		}

		if ([] === $this->old_attributes) return HistoryEvent::EVENT_CREATED;
		if ([] === $this->new_attributes) return HistoryEvent::EVENT_DELETED;
		return HistoryEvent::EVENT_CHANGED;

	}

	/**
	 * @return Users|ActiveQuery
	 * @throws Throwable
	 */
	public function getRelUser() {
		return $this->hasOne(Users::class, ['id' => 'user']);
	}

	/**
	 * Переводит запись из лога в событие истории
	 * @return HistoryEventInterface
	 * @throws Throwable
	 */
	public function getEvent():HistoryEventInterface {
		$result = new HistoryEvent();

		$result->eventType = $this->eventType;

		$result->eventTime = $this->timestamp;
		$result->objectName = $this->model;
		$result->subject = $this->relUser;
		$result->eventIcon = Icons::event_icon($result->eventType);
		$result->actions = $this->eventActions;

		$labelsConfig = $this->getModelRules("eventConfig.eventLabels");

		if (is_callable($labelsConfig)) {
			$result->eventCaption = $labelsConfig($result->eventType, $result->eventTypeName);
		} elseif (is_array($labelsConfig)) {
			$result->eventCaption = ArrayHelper::getValue($labelsConfig, $result->eventType, $result->eventTypeName);
		} else $result->eventCaption = $labelsConfig;

		$result->actionsFormatter = $this->getModelRules("eventConfig.actionsFormatter");

		return $result;
	}

	/**
	 * Вытаскивает из записи описание изменений атрибутов, конвертируя их в набор HistoryEventAction
	 * @return HistoryEventAction[]
	 * @throws Throwable
	 */
	public function getEventActions():array {
		$diff = [];

		$labels = null === $this->loadedModel?[]:$this->loadedModel->attributeLabels();

		foreach ($this->old_attributes as $attributeName => $attributeValue) {
			if (isset($this->new_attributes[$attributeName])) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_CHANGED,
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $this->new_attributes[$attributeName])
				]);
			} else {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_DELETED
				]);

			}
		}
		$e = array_diff_key($this->new_attributes, $this->old_attributes);

		foreach ($e as $attributeName => $attributeValue) {
			if (!isset($this->old_attributes[$attributeName]) || null === ArrayHelper::getValue($this->old_attributes, $attributeName)) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_CREATED
				]);
			}
		}

		return $diff;
	}

	/**
	 * @param string $attributeName Название атрибута, для которого пытаемся найти подстановку
	 * @param mixed $attributeValue Значение атрибута, которому ищем соответствие
	 * @return mixed Подстановленное значение (если найдено, иначе переданное значение)
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	private function SubstituteAttributeValue(string $attributeName, $attributeValue) {
		if (null === $this->loadedModel) return $attributeValue;
		if (null === $attributeConfig = $this->getModelRules("attributes.{$attributeName}")) return $attributeValue;
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
	 * @param int $modelKey
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public function getHistory(int $modelKey):array {/*А зачем возвращать массив, когда можно просто сконфигурировать запрос а-ля find() и юзать его в датапровайдере? Подумать.*/
		if (null === $this->loadedModel) {//не получилось сопоставить класс модели, грузим as is
			return self::find()->where(['model' => $this->model, 'model_key' => $modelKey])->orderBy('at')->all();
		}
		if (null === $requestModel = $this->loadedModel::findModel($modelKey)) {
			$x = $this->loadedModel->formName();
		}

		/** @var LCQuery $findCondition */
		$findCondition = self::find()->where(['model' => $this->loadedModel->formName(), 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели
		/** @var array $relationsRules */
		if (null === $relationsRules = $this->getModelRules('relations')) $relationsRules = [];
		foreach ($relationsRules as $relatedModelClassName => $relationRule) {/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в связанных таблицах*/
			/** @var ActiveRecord $relatedModel */
			$relatedModel = ReflectionHelper::LoadClassByName($relatedModelClassName);
			if (is_callable($relationRule)) {
				$relationRule($findCondition, $relatedModel);
			} elseif (is_array($relationRule)) {
				if (null !== $requestModel = $this->loadedModel::findModel($modelKey)) {//null будет в случае, если объект уже совсем начисто удалён из БД, в этом случае пытаемся сделать хоть какое-то сопоставление
					$linkKey = ArrayHelper::key($relationRule);
					$linkValue = $relationRule[$linkKey];
					$modelKey = $requestModel->$linkKey;
				}
				$findCondition->orWhere("model = '{$relatedModel->formName()}' and (new_attributes->'$.{$linkValue}' = {$modelKey} or old_attributes->'$.{$linkValue}' = {$modelKey})");
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