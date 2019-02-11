<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\models\core\traits\ARExtended;
use app\models\references\refs\RefAttributesTypes;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_attributes".
 *
 * @property int $id
 * @property int $user_id
 * @property int $attribute_id
 * @property ActiveQuery|RelUsersAttributesTypes[] $relUsersAttributesTypes соответствия в таблице типов аттрибута, ассоциированные к этой связке
 * @property ActiveQuery|RefAttributesTypes[] $refAttributesTypes Значения из справочника аттрибутов, ассоциированные с этой связкой
 */
class RelUsersAttributes extends ActiveRecord {
	use Relations;
	use ARExtended;

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
			'user_id' => 'User ID',
			'attribute_id' => 'Attribute ID'
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

}
