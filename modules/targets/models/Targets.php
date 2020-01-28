<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\relations\RelTargetsGroups;
use app\modules\targets\models\relations\RelTargetsTargets;
use app\modules\targets\models\relations\RelTargetsUsers;
use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
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
 *
 * @property ActiveQuery|TargetsIntervals $relTargetsIntervals
 *
 */
class Targets extends ActiveRecordExtended {
	public $startQuarter;
	public $finishQuarter;
	public $year;

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
			[['type', 'result_type', 'daddy', 'startQuarter', 'finishQuarter', 'year'], 'integer'],
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
			'relUsers' => 'Ответственный сотрудник',
			'startQuarter' => 'Начальный квартал',
			'finishQuarter' => 'Конечный квартал',
			'year' => 'Год',
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
	 * @throws Throwable
	 */
	public function setRelParentTarget($parentTarget):void {
		RelTargetsTargets::linkModels($parentTarget, $this);
		if (!empty($parentTarget) && null === $model = self::findModel($parentTarget)) $model->dropCaches();
	}

	/**
	 * Удаление родительской задачи
	 * @param $dropParentTarget
	 * @throws Throwable
	 */
	public function setDropParentTarget($dropParentTarget):void {
		RelTargetsTargets::unlinkModels($dropParentTarget, $this);
		if (!empty($dropParentTarget) && null === $model = self::findModel($dropParentTarget)) $model->dropCaches();
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
	 * @throws Throwable
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
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relTargetsUsers');
	}

	/**
	 * @param Users[]|ActiveQuery $relTargetsUsers
	 * @throws Throwable
	 */
	public function setRelUsers($relTargetsUsers):void {
		RelTargetsUsers::linkModels($this, $relTargetsUsers);
	}

	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array {
		return Yii::$app->cache->getOrSet(static::class."DataOptions", static function() {
			$items = self::find()->active()->all();
			$result = [];

			foreach ($items as $key => $item) {
				/** @var Targets $item */
				$result[$item->id] = [
					'data-typename' => ArrayHelper::getValue($item->relTargetsTypes, 'name'),
					'data-typecolor' => ArrayHelper::getValue($item->relTargetsTypes, 'color'),
					'data-textcolor' => ArrayHelper::getValue($item->relTargetsTypes, 'textcolor')
				];
			}
			return $result;
		});
	}

	/**
	 * @return TargetsIntervals|ActiveQuery
	 */
	public function getRelTargetsIntervals() {
		return $this->hasOne(TargetsIntervals::class, ['target' => 'id']);//пока делаю hasOne, далее посмотрим
	}

}