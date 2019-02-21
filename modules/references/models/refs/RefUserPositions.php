<?php
declare(strict_types = 1);

namespace app\modules\references\models\refs;

use app\helpers\ArrayHelper;
use app\modules\references\models\Reference;
use app\modules\references\models\relations\RelRefUserPositionsBranches;
use app\modules\references\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ref_user_positions".
 *
 * @property int $id
 * @property string $name Название
 * @property int $usedCount Количество объектов, использующих это значение справочника
 * @property string $color//todo
 * @property int $deleted
 *
 * @property RelRefUserPositionsBranches|ActiveQuery $relRefUserPositionsBranches
 * @property RefUserPositionBranches|ActiveQuery $relRefUserPositionBranch
 *
 * @property RelRefUserPositionsTypes[]|ActiveQuery $relRefUserPositionsTypes
 * @property RefUserPositionTypes[]|ActiveQuery $relRefUserPositionTypes
 *
 * @property null|int $branch
 * @property null|int[] $types
 *
 * @property-read null|string $branchName
 * @property-read null|string $typeName
 */
class RefUserPositions extends Reference {
	public $color = null;
	public $menuCaption = 'Должности';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_positions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['branch', 'types'], 'safe'],//relational attributes
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted',
			'usedCount' => 'Использований',
			'branchName' => 'Ветвь',
			'typeName' => 'Тип',
			'branch' => 'Ветвь',
			'types' => 'Тип'
		];
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		Users::updateAll(['position' => $toId], ['position' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)Users::find()->where(['position' => $this->id])->count();
	}

	/**
	 * @return RelRefUserPositionsBranches|ActiveQuery
	 */
	public function getRelRefUserPositionsBranches() {
		return $this->hasOne(RelRefUserPositionsBranches::class, ['position_id' => 'id']);
	}

	/**
	 * @return RefUserPositionBranches|ActiveQuery
	 */
	public function getRelRefUserPositionBranch() {
		return $this->hasOne(RefUserPositionBranches::class, ['id' => 'position_branch_id'])->via('relRefUserPositionsBranches');
	}

	/**
	 * @return null|string
	 */
	public function getBranchName():?string {
		return ArrayHelper::getValue($this->relRefUserPositionBranch, 'name');
	}

	/**
	 * @return string|null
	 */
	public function getTypeName():?string {
		return '<Тип не указан>';
	}

	/**
	 * @return int|null
	 */
	public function getBranch():?int {
		return ArrayHelper::getValue($this->relRefUserPositionBranch, 'id');
	}

	/**
	 * @param mixed $branch
	 * //не можем типизировать null, т.к. может быть передана строка, а делать преобразования бессмысленно
	 */
	public function setBranch($branch):void {
		RelRefUserPositionsBranches::deleteAll(['position_id' => $this->id]);
		RelRefUserPositionsBranches::linkModel($this->id, $branch);//проверки на пустоту делает метод
	}

	/**
	 * @return int[]
	 */
	public function getTypes():array {
		return ArrayHelper::getColumn($this->relRefUserPositionTypes, 'id');
	}

	/**
	 * @param mixed $types
	 */
	public function setTypes($types):void {
		RelRefUserPositionsTypes::deleteAll(['position_id' => $this->id]);
		RelRefUserPositionsTypes::linkModels($this->id, $types);//проверки на пустоту делает метод
	}

	/**
	 * @return RelRefUserPositionsTypes[]|ActiveQuery
	 */
	public function getRelRefUserPositionsTypes() {
		return $this->hasMany(RelRefUserPositionsTypes::class, ['position_id' => 'id']);
	}

	/**
	 * @return RefUserPositionTypes[]|ActiveQuery
	 */
	public function getRelRefUserPositionTypes() {
		return $this->hasMany(RefUserPositionTypes::class, ['id' => 'position_type_id'])->via('relRefUserPositionsTypes');
	}

}
