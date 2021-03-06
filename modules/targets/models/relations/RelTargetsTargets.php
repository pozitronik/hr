<?php
declare(strict_types = 1);

namespace app\modules\targets\models\relations;

use app\components\pozitronik\core\traits\Relations;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use Throwable;

/**
 * This is the model class for table "rel_targets_targets".
 *
 * @property int $id
 * @property int $parent_id Вышестоящая группа
 * @property int $child_id Нижестоящая группа
 * @property int $relation Тип связи -- пока не используем
 */
class RelTargetsTargets extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_targets_targets';
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
			'parent_id' => 'Вышестоящее задание',
			'child_id' => 'Нижестоящее задание',
			'relation' => 'Тип связи'
		];
	}

	/**
	 * Возвращает ID типа связи между заданиями
	 * @param int $parentTargetId
	 * @param int $childTargetId
	 * @return int|null
	 * @throws Throwable
	 */
	public static function getRelationId(int $parentTargetId, int $childTargetId):?int {
		return ArrayHelper::getValue(self::find()->where(['parent_id' => $parentTargetId, 'child_id' => $childTargetId])->select('relation')->one(), 'relation');
	}

}
