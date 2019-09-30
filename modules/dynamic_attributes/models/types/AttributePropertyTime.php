<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use pozitronik\helpers\ArrayHelper;
use app\models\core\ActiveRecordExtended;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\widgets\attribute_field\AttributeFieldWidget;
use Exception;
use kartik\time\TimePicker;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_time".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property string $value Значение
 */
class AttributePropertyTime extends ActiveRecordExtended implements AttributePropertyInterface {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_time';
	}

	/**
	 * @inheritdoc
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
		return [
			['равно', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.value", $searchValue];
			}],
			['не равно', static function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.value", $searchValue];
			}],
			['раньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.value", $searchValue];
			}],
			['позже', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.value", $searchValue];
			}],
			['раньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.value", $searchValue];
			}],
			['позже или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.value", $searchValue];
			}],
			['заполнено', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.value" => null]];
			}],
			['не заполнено', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.value", new Expression('null')];
			}]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id'], 'integer'],
			[['value'], 'safe'],
			[['attribute_id', 'property_id', 'user_id'], 'unique', 'targetAttribute' => ['attribute_id', 'property_id', 'user_id']]
		];
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param bool $formatted
	 * @return mixed
	 */
	public static function getValue(int $attribute_id, int $property_id, int $user_id, bool $formatted = false) {
		return Yii::$app->cache->getOrSet(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", static function() use ($attribute_id, $property_id, $user_id, $formatted) {
			$value = ArrayHelper::getValue(self::getRecord($attribute_id, $property_id, $user_id), 'value');
			return $formatted?Yii::$app->formatter->asTime($value):$value;
		});
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 */
	public static function setValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
		if (null === $record = self::getRecord($attribute_id, $property_id, $user_id)) {
			$record = new self(compact('attribute_id', 'user_id', 'property_id', 'value'));
		} else {
			$record->setAttributes(compact('attribute_id', 'user_id', 'property_id', 'value'));
		}

		if ($record->save()) {
			Yii::$app->cache->set(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", $record->value);
			return true;
		}
		return false;
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
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 * @throws Exception
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return $form->field($property, (string)$property->id)->widget(TimePicker::class, [
			'pluginOptions' => [
				'showSeconds' => true,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				'defaultTime' => false
			],
			'options' => [
				'placeholder' => 'Укажите время'
			]
		])->label(false);
	}

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 * @throws Exception
	 */
	public static function viewField(array $config = []):string {
		return AttributeFieldWidget::widget($config);
	}

	/**
	 * @inheritDoc
	 */
	public static function getAverageValue(array $models):?int {
		return null;
	}
}
