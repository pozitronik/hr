<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\components\pozitronik\core\traits\Relations;
use yii\db\ActiveRecord;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "rel_users_attributes".
 *
 * @property int $id
 * @property int $user_id
 * @property int $attribute_id
 * @property ActiveQuery|RelUsersAttributesTypes[] $relUsersAttributesTypes соответствия в таблице типов аттрибута, ассоциированные к этой связке
 * @property ActiveQuery|RefAttributesTypes[] $refAttributesTypes Значения из справочника аттрибутов, ассоциированные с этой связкой
 *
 * @property ActiveQuery|DynamicAttributes $relDynamicAttribute Атрибут связки
 */
class RelUsersAttributes extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'attribute_id' => [DynamicAttributes::class => 'name'],
				'user_id' => [Users::class => 'username']
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_attributes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'attribute_id'], 'required'],
			[['user_id', 'attribute_id'], 'integer'],
			[['user_id', 'attribute_id'], 'unique', 'targetAttribute' => ['user_id', 'attribute_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'attribute_id' => 'Атрибут'
		];
	}

	/**
	 * @return RelUsersAttributesTypes[]|ActiveQuery
	 */
	public function getRelUsersAttributesTypes() {
		return $this->hasMany(RelUsersAttributesTypes::class, ['user_attribute_id' => 'id']);
	}

	/**
	 * @return RefAttributesTypes[]|ActiveQuery
	 */
	public function getRefAttributesTypes() {
		return $this->hasMany(RefAttributesTypes::class, ['id' => 'type'])->via('relUsersAttributesTypes');
	}

	/**
	 * Вернёт отсортированный по типу набор отношений пользователя к атрибутам. Функция достаточно временная, дальше будет придумано, как выводить список пользовательских атрибутов, скажем, через DataProvider
	 * @param int $userId
	 * @return self[]
	 */
	public static function getUserAttributes(int $userId):array {
		return self::find()->where(['user_id' => $userId])->joinWith('relUsersAttributesTypes')->orderBy('ISNULL(rel_users_attributes_types.type), rel_users_attributes_types.type ASC')->all();
	}

	/**
	 * @param int $userId
	 * @return ActiveQuery
	 */
	public static function getUserAttributesScope(int $userId):ActiveQuery {
		return self::find()->where(['user_id' => $userId])->joinWith(['relUsersAttributesTypes', 'refAttributesTypes']);//->orderBy('ISNULL(rel_users_attributes_types.type), rel_users_attributes_types.type ASC');
	}

	/**
	 * @return DynamicAttributes|ActiveQuery
	 */
	public function getRelDynamicAttribute() {
		return $this->hasOne(DynamicAttributes::class, ['id' => 'attribute_id']);
	}
}

