<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\relations\RelUsersGroups;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\relations\RelTargetsGroups;
use app\modules\targets\models\relations\RelTargetsTargets;
use app\modules\targets\models\relations\RelTargetsUsers;
use app\modules\users\models\Users;
use yii\db\ActiveQuery;

/**
 * Class Targets
 *
 * @property int $id
 * @property int $type
 * @property int $result_type
 * @property string $name
 * @property string $comment
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property boolean $deleted Флаг удаления
 *
 * @property
 * @property ActiveQuery|Targets|null $relParentTarget -- вышестоящая задача целеполагания (если есть)
 * @property ActiveQuery|Targets[] $relChildTarget -- нижестоящие задачи целеполагания
 *
 * @property ActiveQuery|RefTargetsTypes $relTargetsTypes Тип задания через релейшен
 * @property ActiveQuery|RefTargetsResults $relTargetsResults Тип результата задания через релейшен
 *
 * @property ActiveQuery|RelTargetsGroups[] $relTargetsGroups
 * @property ActiveQuery|Groups[] $relGroups
 * @property ActiveQuery|RelTargetsUsers[] $relTargetsUsers
 * @property ActiveQuery|Users[] $relUsers
 */
class Targets extends ActiveRecordExtended {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_targets';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['type', 'name'], 'required'],
			[['type', 'result_type', 'daddy'], 'integer'],
			[['name'], 'string', 'max' => 512],
			[['comment'], 'string'],
			[['create_date', 'relParentTarget', 'relChildTargets', 'relGroups', 'relUsers'], 'safe'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'type' => 'Тип цели',
			'result_type' => 'Тип результата цели',
			'name' => 'Название',
			'comment' => 'Описание',
			'create_date' => 'Дата создания',
			'daddy' => 'Создатель',
			'deleted' => 'Флаг удаления',
			'relParentTarget' => 'Родительское задание',
			'relChildTargets' => 'Входящие задание',
			'relGroups' => 'Группа назначения',
			'relUsers' => 'Ответственный сотрудник'
		];
	}

	/**
	 * @return ActiveQuery|RelTargetsTargets[]
	 */
	public function getRelTargetsTargetsChild() {
		return $this->hasMany(RelTargetsTargets::class, ['parent_id' => 'id']);
	}

	/**
	 * @return ActiveQuery|RelTargetsTargets|null
	 */
	public function getRelTargetsTargetsParent() {
		return $this->hasOne(RelTargetsTargets::class, ['child_id' => 'id']);
	}

	/**
	 * Вернёт вышестоящую задачу целеполагания, если есть
	 * @return Targets|ActiveQuery|null
	 */
	public function getRelParentTarget() {
		return $this->hasOne(self::class, ['id' => 'parent_id'])->via('relTargetsTargetsParent');
	}

	/**
	 * Установка родительской задачи
	 * @param Targets|ActiveQuery|null|string $parentTarget
	 */
	public function setRelParentTarget($parentTarget):void {
		RelTargetsTargets::linkModels($parentTarget, $this);
		if (!empty($parentTarget)) {
			if (null === $model = self::findModel($parentTarget)) $model->dropCaches();
		}
	}

	/**
	 * Удаление родительской задачи
	 * @param Targets|ActiveQuery|null|string $parentTarget
	 */
	public function setDropParentTarget($dropParentTarget):void {
		RelTargetsTargets::unlinkModels($dropParentTarget, $this);
		if (!empty($dropParentTarget)) {
			if (null === $model = self::findModel($dropParentTarget)) $model->dropCaches();
		}
	}

	/**
	 * @return RefTargetsTypes|ActiveQuery
	 */
	public function getRelTargetsTypes() {
		return $this->hasOne(RefTargetsTypes::class, ['id' => 'type']);
	}

	/**
	 * @return RefTargetsResults|ActiveQuery
	 */
	public function getRelTargetsResults() {
		return $this->hasOne(RefTargetsResults::class, ['id' => 'result_type']);
	}

	/**
	 * @return RelTargetsGroups[]|ActiveQuery
	 */
	public function getRelTargetsGroups() {
		return $this->hasMany(RelTargetsGroups::class, ['target_id' => 'id']);
	}

	/**
	 * @return Groups[]|ActiveQuery
	 */
	public function getRelGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relTargetsGroups');
	}

	/**
	 * @param Groups[]|ActiveQuery $relTargetsGroups
	 */
	public function setRelGroups($relTargetsGroups):void {
		RelTargetsGroups::linkModels($this, $relTargetsGroups);
	}

	/**
	 * @return RelTargetsUsers[]|ActiveQuery
	 */
	public function getRelTargetsUsers() {
		return $this->hasMany(RelTargetsUsers::class, ['target_id' => 'id']);
	}

	/**
	 * @return Users[]|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'users_id'])->via('relTargetsUsers');
	}

	/**
	 * @param Users[]|ActiveQuery $relTargetsUsers
	 */
	public function setRelUsers($relTargetsUsers):void {
		RelTargetsGroups::linkModels($this, $relTargetsUsers);
	}
}