<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\helpers\ArrayHelper;
use app\models\references\refs\RefAttributesTypes;
use app\models\references\refs\RefGroupRelationTypes;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_attributes_types".
 *
 * @property int $user_attribute_id
 * @property int $type
 *
 * @property ActiveQuery|RelUsersAttributes[] $relUsersAttributes
 * @property ActiveQuery|RefGroupRelationTypes refGroupsRelationType Типы связей (справочник)
 */
class RelUsersAttributesTypes extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rel_users_attributes_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_attribute_id', 'type'], 'required'],
			[['user_attribute_id', 'type'], 'integer'],
			[['user_attribute_id', 'type'], 'unique', 'targetAttribute' => ['user_attribute_id', 'type']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'user_attribute_id' => 'ID связки пользователь/атрибут',
			'type' => 'Тип',
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
	public static function getAttributeTypes(int $user, int $attribute):array {
		return ArrayHelper::getColumn(self::find()->joinWith('relUsersAttributes')->where(['rel_users_attributes.user_id' => $user, 'rel_users_attributes.attribute_id' => $attribute])->select('rel_users_attributes_types.type')->all(), 'type');
	}

	/**
	 * @return RefGroupRelationTypes|ActiveQuery
	 */
	public function getRefGroupsRelationTypes() {
		return $this->hasOne(RefAttributesTypes::class, ['id' => 'type']);
	}

}
