<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\user\CurrentUser;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\relations\RelTargetsTargets;
use yii\db\ActiveQuery;

/**
 * Class Targets
 *
 * @property int $id
 * @property int $type
 * @property int $result_type
 * @property int $group_id
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
			[['type', 'group_id', 'name'], 'required'],
			[['type', 'result_type', 'group_id', 'daddy'], 'integer'],
			[['name'], 'string', 'max' => 512],
			[['comment'], 'string'],
			[['create_date', 'relParentTarget', 'relChildTargets'], 'safe'],
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
			'group_id' => 'Исполняющая группа',
			'name' => 'Название',
			'comment' => 'Описание',
			'create_date' => 'Дата создания',
			'daddy' => 'Создатель',
			'deleted' => 'Флаг удаления',
			'relParentTarget' => 'Родительское задание',
			'relChildTargets' => 'Входящие задание'
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

}