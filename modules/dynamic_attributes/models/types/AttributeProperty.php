<?php /** @noinspection UndetectableTableInspection */
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\widgets\attribute_field\AttributeFieldWidget;
use Exception;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Class ActiveAttributeProperty
 * @package app\modules\dynamic_attributes\models\types
 * Прототипирую методы хранения динамических свойств атрибута
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property mixed|null $value Значение
 */
class AttributeProperty extends ActiveRecord implements AttributePropertyInterface {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		throw new InvalidConfigException('"'.static::class.'" нельзя вызывать напрямую.');
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'attribute_id' => 'ID атрибута',
			'property_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value' => 'Значение'
		];
	}

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [];//по умолчанию не поддерживается поиск в базе
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [DynamicAttributePropertyAggregation::AGGREGATION_COUNT];
	}

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 * @throws Exception
	 */
	public static function viewField(array $config = []):string {
		return AttributeFieldWidget::widget($config);//отдадим отображение по умолчанию
	}

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return $form->field($property, (string)$property->id)->textInput(['style' => 'resize: none;'])->label(false);//самый простой и универсальный редактор по умолчанию
	}

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation -- результат агрегации в модели
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		if (DynamicAttributePropertyAggregation::AGGREGATION_COUNT === $aggregation) {
			return new DynamicAttributePropertyAggregation([
				'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
				'value' => DynamicAttributePropertyAggregation::AggregateIntCount($models, $dropNullValues)
			]);
		}
		return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
	}

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @return self|ActiveRecord|null
	 */
	public static function getRecord(int $attribute_id, int $property_id, int $user_id):?self {
		return self::find()->where(compact('attribute_id', 'property_id', 'user_id'))->one();
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @return mixed
	 * @throws Throwable
	 */
	public static function loadValue(int $attribute_id, int $property_id, int $user_id) {
		return Yii::$app->cache->getOrSet(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", static function() use ($attribute_id, $property_id, $user_id) {
			return ArrayHelper::getValue(self::getRecord($attribute_id, $property_id, $user_id), 'value');
		});
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return boolean
	 */
	public static function saveValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
		if (null === $record = self::getRecord($attribute_id, $property_id, $user_id)) {
			$record = new static(compact('attribute_id', 'user_id', 'property_id', 'value'));
		} else {
			$record->setAttributes(compact('attribute_id', 'user_id', 'property_id', 'value'));
		}

		if ($record->save()) {
			Yii::$app->cache->set(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", $record->value);
			return true;
		}
		return false;
	}

}