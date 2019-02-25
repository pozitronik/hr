<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\helpers\ArrayHelper;
use app\models\core\traits\ARExtended;
use app\modules\groups\models\references\RefGroupRelationTypes;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_groups_groups".
 *
 * @property int $id
 * @property int $parent_id Вышестоящая группа
 * @property int $child_id Нижестоящая группа
 * @property int $relation Тип связи
 * @property ActiveQuery|RefGroupRelationTypes refGroupsRelationTypes Типы связей (справочник)
 */
class RelGroupsGroups extends ActiveRecord {
	use Relations;
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_groups_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['parent_id', 'child_id'], 'required'],
			[['parent_id', 'child_id', 'relation'], 'integer'],
			[['parent_id', 'child_id', 'relation'], 'unique', 'targetAttribute' => ['parent_id', 'child_id', 'relation']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'parent_id' => 'Вышестоящая группа',
			'child_id' => 'Нижестоящая группа',
			'relation' => 'Тип связи'
		];
	}

	/**
	 * @return ActiveQuery|RefGroupRelationTypes
	 */
	public function getRefGroupsRelationTypes() {
		return $this->hasOne(RefGroupRelationTypes::class, ['id' => 'relation']);
	}

	/**
	 * Возвращает ID типа связи между группами
	 * @param int $parentGroupId
	 * @param int $childGroupId
	 * @return int|null
	 * @throws Throwable
	 */
	public static function getRelationId(int $parentGroupId, int $childGroupId):?int {
		return ArrayHelper::getValue(self::find()->where(['parent_id' => $parentGroupId, 'child_id' => $childGroupId])->select('relation')->one(), 'relation');
	}

	/**
	 * Вернёт цвет, присвоенный этому типу связи
	 * @param int $parentGroupId
	 * @param int $childGroupId
	 * @return false|string
	 */
	public static function getRelationColor(int $parentGroupId, int $childGroupId) {
		/** @var self|null $model */
		if (null !== $model = self::find()->where(['parent_id' => $parentGroupId, 'child_id' => $childGroupId])->one()) {
			if (null === $model->refGroupsRelationTypes) {
				return false;
			}
			return $model->refGroupsRelationTypes->color;
		}
		return false;
	}
}
