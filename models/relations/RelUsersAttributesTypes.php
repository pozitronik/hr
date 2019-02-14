<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\helpers\ArrayHelper;
use app\models\references\refs\RefAttributesTypes;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_attributes_types".
 *
 * @property int $user_attribute_id
 * @property int $type
 *
 * @property ActiveQuery|RelUsersAttributes[] $relUsersAttributes
 * @property ActiveQuery|RefAttributesTypes $refAttributesType Типы связей (справочник)
 */
class RelUsersAttributesTypes extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_attributes_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_attribute_id', 'type'], 'required'],
			[['user_attribute_id', 'type'], 'integer'],
			[['user_attribute_id', 'type'], 'unique', 'targetAttribute' => ['user_attribute_id', 'type']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'user_attribute_id' => 'ID связки пользователь/атрибут',
			'type' => 'Тип'
		];
	}

	/**
	 * @return RelUsersAttributes[]|ActiveQuery
	 */
	public function getRelUsersAttributes() {
		return $this->hasMany(RelUsersAttributes::class, ['id' => 'user_attribute_id']);
	}

	/**
	 * Возвращает id типов аттрибута для пользователя
	 * @param int $user
	 * @param int $attribute
	 * @return int[]
	 */
	public static function getAttributeTypesId(int $user, int $attribute):array {
		return ArrayHelper::getColumn(self::getAttributeTypes($user, $attribute), 'type');
	}

	/**
	 * Возвращает массив релейшенов типов аттрибута для пользователя
	 * @param int $user
	 * @param int $attribute
	 * @return self[]
	 */
	public static function getAttributeTypes(int $user, int $attribute):array {
		return self::find()->joinWith('relUsersAttributes')->where(['rel_users_attributes.user_id' => $user, 'rel_users_attributes.attribute_id' => $attribute])->select('rel_users_attributes_types.type')->all();
	}

	/**
	 * Возвращает непосредственно значения справочника типов аттрибутов, ассоциированных к связи между пользователем и аттрибутом
	 * @param int $user
	 * @param int $attribute
	 * @return array
	 * @throws Throwable
	 */
	public static function getRefAttributesTypes(int $user, int $attribute):array {
		return RefAttributesTypes::findModels(self::getAttributeTypesId($user, $attribute));
	}

	/**
	 * @return RefAttributesTypes|ActiveQuery
	 */
	public function getRefAttributesType() {
		return $this->hasOne(RefAttributesTypes::class, ['id' => 'type']);
	}

	/**
	 * Присваивает тип отношения для существующей связки пользователь/атрибут
	 * @param int|int[] $typeId
	 * @param int $userId
	 * @param int $attributeId
	 * @return bool
	 */
	public static function setAttributeTypeForUser($typeId, int $userId, int $attributeId):bool {
		$rel = RelUsersAttributes::find()->where(['user_id' => $userId, 'attribute_id' => $attributeId])->one();
		if ($rel) {
			if (is_array($typeId)) {
				foreach ($typeId as $type) {
					$relUsersAttributesTypes = new self(['user_attribute_id' => $rel->id, 'type' => $type]);//не заморачиваемся с проверкой уже существующих записей, в данном случае их можно просто игнорировать
					$relUsersAttributesTypes->save();
				}
			} else {
				$relUsersAttributesTypes = new self(['user_attribute_id' => $rel->id, 'type' => $typeId]);
				$relUsersAttributesTypes->save();
			}
			return true;//Результат дальше особо не анализируется, что плохо
		}
		return false;//Попытка добавления типа к несуществующей связи между пользователем и атрибутом
	}

	/**
	 * Удаляет тип отношения для существующей связки пользователь/атрибут
	 * @param int|int[] $typeId
	 * @param int $userId
	 * @param int $attributeId
	 * @return bool
	 */
	public static function clearAttributeTypeForUser($typeId, int $userId, int $attributeId):bool {
		$rel = RelUsersAttributes::find()->where(['user_id' => $userId, 'attribute_id' => $attributeId])->one();
		if ($rel) {
			if (is_array($typeId)) {
				foreach ($typeId as $type) {
					self::deleteAll(['user_attribute_id' => $rel->id, 'type' => $type]);
				}
			} else {
				self::deleteAll(['user_attribute_id' => $rel->id, 'type' => $typeId]);
			}
			return true;//Результат дальше особо не анализируется, что плохо
		}
		return false;//Попытка добавления типа к несуществующей связи между пользователем и атрибутом
	}

	/**
	 * Удаляет все типы отношений для существующей связки пользователь/атрибут
	 * @param int $userId
	 * @param int $attributeId
	 * @return bool
	 */
	public static function clearAllAttributeTypesForUser(int $userId, int $attributeId):bool {
		$rel = RelUsersAttributes::find()->where(['user_id' => $userId, 'attribute_id' => $attributeId])->one();
		if ($rel) {
			self::deleteAll(['user_attribute_id' => $rel->id]);
			return true;//Результат дальше особо не анализируется, что плохо
		}
		return false;//Попытка добавления типа к несуществующей связи между пользователем и атрибутом
	}

}
