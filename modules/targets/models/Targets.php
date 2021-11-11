<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\components\pozitronik\helpers\DateHelper;
use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;
use app\components\pozitronik\core\models\lcquery\LCQuery;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\relations\RelTargetsGroups;
use app\modules\targets\models\relations\RelTargetsTargets;
use app\modules\targets\models\relations\RelTargetsUsers;
use app\modules\users\models\Users;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * Class Targets
 *
 * @property int $id
 * @property int $type
 * @property int|null $result_type
 * @property string $name
 * @property string $comment
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property bool $deleted Флаг удаления
 *
 * @property ActiveQuery|Targets $relParentTarget -- вышестоящая задача целеполагания (если есть)
 * @property ActiveQuery|Targets[] $relChildTargets -- нижестоящие задачи целеполагания
 *
 * @property ActiveQuery|RefTargetsTypes $relTargetsTypes Тип задания через релейшен
 * @property ActiveQuery|RefTargetsResults $relTargetsResults Тип результата задания через релейшен
 *
 * @property ActiveQuery|RelTargetsGroups[] $relTargetsGroups
 * @property ActiveQuery|Groups[] $relGroups
 * @property ActiveQuery|RelTargetsUsers[] $relTargetsUsers
 * @property ActiveQuery|Users[] $relUsers
 *
 * @property ActiveQuery|TargetsPeriods $relTargetsPeriods
 *
 * @property-read bool $isMirrored -- зеркальная ли цель
 * @property-read bool $isFinal -- финальная цель (нет нижестоящих уровней, но есть финальные атрибуты)
 *
 * @property-read string $logo -- фейковое свойство, нужно для отображения на графе
 */
class Targets extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_targets';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
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
			'result_type' => 'Тип результата',
			'name' => 'Название',
			'comment' => 'Описание',
			'create_date' => 'Дата создания',
			'daddy' => 'Создатель',
			'deleted' => 'Флаг удаления',
			'relParentTarget' => 'Родительское задание',
			'relChildTargets' => 'Входящие задание',
			'relGroups' => 'Группа назначения',
			'relUsers' => 'Ответственный сотрудник',
			'relTargetsPeriods' => 'Сроки'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsTargetsChild():ActiveQuery {
		return $this->hasMany(RelTargetsTargets::class, ['parent_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsTargetsParent():ActiveQuery {
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
	 * @param mixed $parentTarget
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
	 * Вернёт нижестоящие задачу целеполагания, если есть
	 * @return Targets[]|ActiveQuery
	 */
	public function getRelChildTargets() {
		return $this->hasMany(self::class, ['id' => 'child_id'])->via('relTargetsTargetsChild');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsTypes():ActiveQuery {
		return $this->hasOne(RefTargetsTypes::class, ['id' => 'type']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsResults():ActiveQuery {
		return $this->hasOne(RefTargetsResults::class, ['id' => 'result_type']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsGroups():ActiveQuery {
		return $this->hasMany(RelTargetsGroups::class, ['target_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroups():ActiveQuery {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relTargetsGroups');
	}

	/**
	 * @param mixed $relTargetsGroups
	 * @throws Throwable
	 */
	public function setRelGroups(mixed $relTargetsGroups):void {
		RelTargetsGroups::linkModels($this, $relTargetsGroups);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargetsUsers():ActiveQuery {
		return $this->hasMany(RelTargetsUsers::class, ['target_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsers():ActiveQuery {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relTargetsUsers');
	}

	/**
	 * @param mixed $relTargetsUsers
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
	 * @return ActiveQuery
	 */
	public function getRelTargetsPeriods():ActiveQuery {
		//Не включать условия в этот геттер, он есть и всё. Иначе будут падать релейшены
		return $this->hasOne(TargetsPeriods::class, ['target_id' => 'id']);
	}

	/**
	 * @param int $quarter
	 * @return LCQuery
	 */
	public function getQuarterTargets(int $quarter = TargetsPeriods::PERIOD_NOT_SET):LCQuery {
		$query = self::find()->active()->joinWith(['relTargetsTargetsParent parent', 'relTargetsPeriods'])->where(['parent_id' => $this->id]);
		switch ($quarter) {
			default:
				if (!in_array($quarter, [1, 2, 3, 4])) throw new InvalidArgumentException('Only four quarters in year');
				$query->andWhere(["sys_targets_periods.q{$quarter}" => true]);
			break;
			case 0:
				$query->andWhere(["sys_targets_periods.is_year" => true]);
			break;
			case -1:
				$query->andWhere(["sys_targets_periods.is_year" => false])
					->andWhere(["sys_targets_periods.q1" => false])
					->andWhere(["sys_targets_periods.q2" => false])
					->andWhere(["sys_targets_periods.q3" => false])
					->andWhere(["sys_targets_periods.q4" => false]);
			break;
		}
		return $query;
	}

	/**
	 * @return bool
	 */
	public function getIsMirrored():bool {
		return 1 < (count($this->relGroups) + count($this->relUsers));
	}

	/**
	 * @return bool
	 */
	public function getIsFinal():bool {//плохое временное решение
		return $this->relTargetsTypes->isFinal;
	}

	/**
	 * @return string
	 */
	public function getLogo():string {
		return "/img/targets/".mb_strtolower($this->relTargetsTypes->name).".png";
	}

	/**
	 * todo
	 */
	public function dropCaches():void {

	}

	/**
	 * Все итоговые цели пользователя
	 * @param Users $user
	 * @return LCQuery
	 * @throws InvalidConfigException
	 */
	public static function FindUserTargetsScope(Users $user):LCQuery {
		$userCommandsId = ArrayHelper::getColumn($user->relGroups, 'id');
		$finalTypeId = RefTargetsTypes::final()->id;

		return static::find()->active()
			->joinWith(['relGroups', 'relUsers'])
			->where(['sys_targets.type' => $finalTypeId])
			->andFilterWhere(['sys_groups.id' => $userCommandsId])
			->orFilterWhere(['sys_users.id' => $user->id]);
	}

	/**
	 * Все итоговые цели пользователя
	 * @param Groups $group
	 * @return LCQuery
	 * @throws InvalidConfigException
	 */
	public static function FindGroupTargetsScope(Groups $group):LCQuery {
		$finalTypeId = RefTargetsTypes::final()->id;

		return static::find()->active()
			->joinWith(['relGroups', 'relUsers'])
			->where(['sys_targets.type' => $finalTypeId])
			->andWhere(['sys_groups.id' => $group->id]);
	}

}