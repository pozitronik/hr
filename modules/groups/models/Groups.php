<?php
declare(strict_types = 1);

namespace app\modules\groups\models;

use app\components\pozitronik\core\traits\ARExtended;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\modules\vacancy\models\Vacancy;
use app\components\pozitronik\helpers\ArrayHelper;
use app\components\pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;
use app\components\pozitronik\core\models\core_module\PluginTrait;
use app\components\pozitronik\core\models\lcquery\LCQuery;
use app\components\pozitronik\core\traits\Upload;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\users\models\references\RefUserRoles;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name Название
 * @property int $type Тип группы
 * @property string $comment Описание
 * @property int|null $daddy Пользователь, создавший группу
 * @property string $logotype Название файла-логотипа
 * @property ActiveQuery|Users[] $relUsers Пользователи в группе
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Связь с релейшеном пользователей
 * @property ActiveQuery|Groups[] $relChildGroups Группы, дочерние по отношению к текущей
 * @property-write array $dropChildGroups Свойство для передачи массива отлинкуемых дочерних групп//todo: подозрительно, проверить & документировать
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsParent Релейшен групп для получения дочерних групп
 * @property array $dropParentGroups Свойство для передачи массива отлинкуемых родительских групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsChild Релейшен групп для получения родительских групп
 * @property ActiveQuery|Groups[] $relParentGroups Группы, родительские по отношению к текущей
 * @property ActiveQuery|RefGroupTypes $relGroupTypes Тип группы через релейшен
 *
 * @property ActiveQuery|Vacancy[] $relVacancy Релейшен к вакансиям группы
 *
 * @property-read Users[] $leaders Пользюки, прописанные в группе с релейшеном лидера (владелец/руководитель)
 * @property-read Users[] $important Пользюки, прописанные в группе с релейшеном важности (продуктовнер)
 * @property-read Users|null $leader Один пользователь из лидеров (для презентации)
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesLeader
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesImportant
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles
 * @property array $rolesInGroup
 * @property array $dropUsers
 * @property bool $deleted
 * @property LCQuery $relUsersHierarchy Пользователи во всех группах вниз по иерархии
 * @property-read string $logo Полный путь к логотипу/дефолтной картинке
 *
 * @property-read int $usersCount Количество пользователей в группе
 * @property-read int $vacancyCount Количество вакансий в группе
 *
 * @property-read int $childGroupsCount Количество подгрупп (следующего уровня)
 *
 */
class Groups extends ActiveRecord {
	use Upload;
	use PluginTrait;
	use ARExtended;

	public const LOGO_IMAGE_DIRECTORY = '@app/web/group_logotypes/';

	/*Переменная для инстанса заливки логотипа*/
	public $upload_image;

	/**
	 * Правила отображения модели в истории
	 * @return array
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'daddy' => static function(string $attributeName, $attributeValue) {
					return ArrayHelper::getValue(Users::findModel($attributeValue), 'username', $attributeValue);
				},
				'type' => [RefGroupTypes::class => 'name'],
//				'deleted' => '<hidden>',
			],
			'relations' => [
				RelUsersGroups::class => ['id' => 'group_id']
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_groups';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['daddy', 'type'], 'integer'],
			[['create_date'], 'safe'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['name'], 'string', 'max' => 512],
			[['logotype'], 'string', 'max' => 255],
			[['relChildGroups', 'dropChildGroups', 'relParentGroups', 'dropParentGroups', 'relUsers', 'dropUsers'], 'safe'],
			[['upload_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1048576],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'type' => 'Тип группы',
			'comment' => 'Описание',
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'leaders' => 'Руководители',
			'logotype' => 'Логотип',
			'upload_image' => 'Логотип',
			'deleted' => 'Deleted',
			'usersCount' => 'Количество пользователей',
			'childGroupsCount' => 'Количество подгрупп'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['group_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelVacancy() {
		return $this->hasMany(Vacancy::class, ['group' => 'id']);
	}

	/**
	 * @param ActiveQuery|Users[] $relGroupsUsers
	 * @throws Throwable
	 */
	public function setRelUsers(ActiveQuery|array $relGroupsUsers):void {
		RelUsersGroups::linkModels($relGroupsUsers, $this);
	}

	/**
	 * @param array $dropUsers
	 * @throws Throwable
	 */
	public function setDropUsers(array $dropUsers):void {
		RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $dropUsers])->select('id')]);
		RelUsersGroups::unlinkModels($dropUsers, $this);
	}

	/**
	 * Релейшен к назначению ролей в этой группе
	 * @return ActiveQuery
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['user_group_id' => 'id'])->via('relUsersGroups');
	}

	/**
	 * Все назначенные роли в этой группе
	 * @return ActiveQuery
	 */
	public function getRelRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles');
	}

	/**
	 * Все роли боссов в этой группе
	 * @return ActiveQuery
	 */
	public function getRelRefUserRolesLeader() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles')->where(['ref_user_roles.boss_flag' => true]);
	}

	/**
	 * Все роли важных шишек в этой группе
	 * @return ActiveQuery
	 */
	public function getRelRefUserRolesImportant() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles')->where(['ref_user_roles.importance_flag' => true]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroupsGroupsChild() {
		return $this->hasMany(RelGroupsGroups::class, ['parent_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return ActiveQuery
	 */
	public function getRelChildGroups() {
		return $this->hasMany(self::class, ['id' => 'child_id'])->via('relGroupsGroupsChild');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param ActiveQuery|Groups[] $childGroups
	 * @throws Throwable
	 */
	public function setRelChildGroups(ActiveQuery|array $childGroups):void {
		RelGroupsGroups::linkModels($this, $childGroups);
		$this->dropCaches();
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropChildGroups
	 * @throws Throwable
	 */
	public function setDropChildGroups(array $dropChildGroups):void {
		RelGroupsGroups::unlinkModels($this, $dropChildGroups);
		$this->dropCaches();
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroupsGroupsParent() {
		return $this->hasMany(RelGroupsGroups::class, ['child_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return ActiveQuery
	 */
	public function getRelParentGroups() {
		return $this->hasMany(self::class, ['id' => 'parent_id'])->via('relGroupsGroupsParent');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param ActiveQuery|Groups[] $parentGroups
	 * @throws Throwable
	 */
	public function setRelParentGroups(ActiveQuery|array $parentGroups):void {
		RelGroupsGroups::linkModels($parentGroups, $this);
		if (!empty($parentGroups)) {
			foreach ((array)$parentGroups as $group) {
				if (null === $model = self::findModel($group)) $model->dropCaches();
			}
		}
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropParentGroups
	 * @throws Throwable
	 */
	public function setDropParentGroups(array $dropParentGroups):void {
		RelGroupsGroups::unlinkModels($dropParentGroups, $this);
		if (!empty($dropParentGroups)) {
			foreach ($dropParentGroups as $group) {
				if (null === $model = self::findModel($group)) $model->dropCaches();
			}
		}
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroupTypes() {//todo: Большое количество повторных запросов, посмотреть
		return $this->hasOne(RefGroupTypes::class, ['id' => 'type']);
	}

	/**
	 * Вернёт всех пользователей в группе с меткой босса
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getLeaders():array {
		return $this->getRelUsers()->joinWith(['relRefUserRolesLeader'], false)->where(['rel_users_groups.group_id' => $this->id])->all();
	}

	/**
	 * Вернёт всех пользователей в группе с меткой важной шишки
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getImportant():array {
		return $this->getRelUsers()->joinWith(['relRefUserRolesImportant'], false)->where(['rel_users_groups.group_id' => $this->id])->all();
	}

	/**
	 * Если у группы есть лидеры - покажет одного. Презентационная штука.
	 * @return Users
	 */
	public function getLeader():Users {
		return $this->leaders?$this->leaders[0]:new Users();
	}

	/**
	 * Простая функция проверки, является ли пользователь лидером в этой группе
	 * @param Users|null $user
	 * @return bool
	 */
	public function isLeader(?Users $user):bool {
		if (null === $user) return false;
		return self::find()->joinWith(['relRefUserRoles'])->where(['ref_user_roles.boss_flag' => true, 'rel_users_groups.group_id' => $this->id, 'rel_users_groups.user_id' => $user->id])->count() > 0;
	}

	/**
	 * Добавляет массив ролей пользователя к группе
	 * @param array<int, array<int>> $userRoles
	 * @throws Throwable
	 */
	public function setRolesInGroup(array $userRoles):void {
		foreach ($userRoles as $user => $roles) {
			$currentUserGroupId = RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $user])->select('id')->one();
			$currentUserRoles = RelUsersGroupsRoles::find()->where(['user_group_id' => $currentUserGroupId])->all();
			$currentUserRolesId = ArrayHelper::getColumn($currentUserRoles, 'role');
			$deletedRolesId = array_diff($currentUserRolesId, $roles);//id удаляемых ролей
			/*Сначала удаляем роли, которых нет в обновлённом списке*/
			RelUsersGroupsRoles::deleteAll(['user_group_id' => $currentUserGroupId, 'role' => $deletedRolesId]);
			/** @var array $roles */
			$addedRolesId = array_diff($roles, $currentUserRolesId);//id добавляемых ролей
			foreach ($addedRolesId as $role) {
				/*Добавляем только те роли, которых ещё нет*/
				RelUsersGroupsRoles::setRoleInGroup((int)$role, $this->id, $user);
			}
		}
	}

	/**
	 * Пытается подгрузить файл картинки, если он есть
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public function uploadLogotype():bool {
		if (null !== $imageFile = $this->uploadFile(self::LOGO_IMAGE_DIRECTORY, (string)$this->id, null, 'upload_image', PATHINFO_BASENAME)) {
			$this->setAndSaveAttribute('logotype', $imageFile);
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getLogo():string {
		return is_file(Yii::getAlias(self::LOGO_IMAGE_DIRECTORY.$this->logotype))?"/group_logotypes/{$this->logotype}":"/img/group_logo.png";
	}

	/**
	 * Собираем рекурсивно айдишники всех групп вниз по иерархии
	 * @param int|null $initialId Параметр для учёта рекурсии
	 * @return array<int>
	 * @fixme: возникает рекурсия, если пользователь находится в группах, разнесённых по дереву наследований
	 * @fixme собирать рекурсий массивом!
	 */
	public function collectRecursiveIds(?int $initialId = null):array {
		return Yii::$app->cache->getOrSet(static::class."CollectRecursiveIds".$this->id, function() use ($initialId) {
			$initialId = $initialId??$this->id;
			$groupsId = [[]];//Сюда соберём айдишники всех обходимых групп
			/** @var Groups $childGroup */
			foreach ((array)$this->relChildGroups as $childGroup) {
				if ($initialId !== $childGroup->id) {//избегаем рекурсии
					$groupsId[] = $childGroup->collectRecursiveIds($initialId);
				}

			}
			$groupsId = array_merge(...$groupsId);
			$groupsId[] = $this->id;
			return $groupsId;
		});

	}

/*
	public function collectRecursiveIds(array &$stackedId = []):array {
		return Yii::$app->cache->getOrSet(static::class."CollectRecursiveIds".$this->id, function() use ($stackedId) {
			if (!in_array($this->id, $stackedId)) $stackedId[] = $this->id;
			$groupsId = [[]];//Сюда соберём айдишники всех обходимых групп
			foreach ((array)$this->relChildGroups as $childGroup) {
				if (in_array($childGroup->id, $stackedId)) {//избегаем рекурсии
					return [];
				} else {
					$stackedId[] = $childGroup->id;
					$groupsId[] = $childGroup->collectRecursiveIds($stackedId);
				}

			}
			$groupsId = array_merge(...$groupsId);
			$groupsId[] = $this->id;
			return $groupsId;
		});

	}
*/
	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array {
		return Yii::$app->cache->getOrSet(static::class."DataOptions", static function() {
			$items = self::find()->active()->all();
			$result = [];
			/** @var self $item */
			foreach ($items as $key => $item) {
				$result[$item->id] = [
					'data-logo' => $item->logo,
					'data-typename' => ArrayHelper::getValue($item->relGroupTypes, 'name'),
					'data-typecolor' => ArrayHelper::getValue($item->relGroupTypes, 'color'),
					'data-textcolor' => ArrayHelper::getValue($item->relGroupTypes, 'textcolor')
				];
			}
			return $result;
		});
	}

	/**
	 * Прототипируемая функция: обходим всю иерархию групп вниз по дереву, возвращаем всех пользователей групп в иерархии.
	 * Голым запросом не сделано, потому что в MySQL рекурсивные вызовы (а без них придётся задавать глубину обхода вручную) появились только в 8.0, и с ними я просто не знаком.
	 * К тому же непонятно, как это делать средствами фреймворка. В общем, прототипирую идею, дальше как получится.
	 */
	public function getRelUsersHierarchy():LCQuery {
		return Users::find()->joinWith(['relUsersGroups'])->where(['rel_users_groups.group_id' => $this->collectRecursiveIds()])->active();
	}

	/**
	 * @return int
	 */
	public function getUsersCount():int {
		return (int)$this->getRelUsers()->count();
	}

	/**
	 * @return int
	 */
	public function getChildGroupsCount():int {//todo: торможение, разобраться
		$id = $this->id;
		return Yii::$app->cache->getOrSet(static::class."ChildGroupsCount{$this->id}", static function() use ($id) {
			return (int)self::find()->leftJoin('rel_groups_groups', 'rel_groups_groups.parent_id = sys_groups.id')->where(['rel_groups_groups.parent_id' => $id])->count();
		});
	}

	/**
	 * Удаляет все кеши, связанные с группой
	 */
	public function dropCaches():void {
		Yii::$app->cache->delete(static::class."CollectRecursiveIds{.$this->id}");
		Yii::$app->cache->delete(static::class."DataOptions");
		Yii::$app->cache->delete(static::class."getGroupVacancyTypeData{$this->id}");
//		Yii::$app->cache->delete(static::class."HierarchyTree".$this->id);
	}

	/**
	 * Строит дерево иерархии id групп с учётом рекурсии
	 * @param array $stackedId Массив всех обойдённых групп (плоский)
	 * @return array Массив всех обойдённых групп (иерархический)
	 */
	public function buildHierarchyTree(array &$stackedId = []):array {
		if (!in_array($this->id, $stackedId)) $stackedId[] = $this->id;
		$hierarchyTree = [];
		/** @var self[] $childGroups */
		$childGroups = Yii::$app->cache->getOrSet(static::class."getRelChildGroups{$this->id}", function() {
			return $this->getRelChildGroups()->orderBy('name')->active()->all();
		});
		foreach ($childGroups as $childGroup) {
			if (in_array($childGroup->id, $stackedId)) {
				$hierarchyTree[$this->id][$childGroup->id] = $childGroup->id;
			} else {
				$stackedId[] = $childGroup->id;
				$hierarchyTree[$this->id][$childGroup->id] = $childGroup->buildHierarchyTree($stackedId);
			}

		}
		return $hierarchyTree;

	}

	/**
	 * @return int
	 */
	public function getVacancyCount():int {
		return (int)$this->getRelVacancy()->count();
	}

	/**
	 * Строит срез по типам должностей, демо-прототип
	 * @return RefUserPositionTypes[]
	 * Нужно заморочиться и переписать это на голый SQL, но у меня не хватает мозгов, поэтому обхожусь кешированием
	 */
	public function getGroupPositionTypeData():array {
		$id = $this->id;
		return Yii::$app->cache->getOrSet(static::class."getGroupPositionTypeData{$id}", static function() use ($id) {
			return self::getGroupScopePositionTypeData([$id]);
		});
	}

	/**
	 * Строит срез по типам должностей для перечисленного набора групп
	 * @param int[] $scope
	 * @return RefUserPositionTypes[]
	 */
	public static function getGroupScopePositionTypeData(array $scope):array {
		$cacheKey = json_encode($scope);
		return Yii::$app->cache->getOrSet(static::class."getGroupScopePositionTypeData{$cacheKey}", static function() use ($scope) {
			/*Временный и дубовый код. После того, как логика работы с типами должностей устаканится, нужно будет переписать, оптимальнее всего - в SQL-вью, возвращающую нужные данные без необходимости крутить циклы*/
			$allPositionTypes = RefUserPositionTypes::find()->active()->all();//Все справочники
			$countersArray = [];
			$groupUsers = Users::find()->distinct()->active()->joinWith(['relGroups'], false)->where(['sys_groups.id' => $scope])->all();//get all active users ids in scope
			foreach ($groupUsers as $user) {
				$userPositionTypes = $user->relRefUserPositionsTypesAny;
				foreach ($userPositionTypes as $userPositionType) {
					$countersArray[$userPositionType->id] = ArrayHelper::getValue($countersArray, $userPositionType->id, 0) + 1;
				}
			}
			/** @var RefUserPositionTypes[] $allPositionTypes */
			foreach ($allPositionTypes as $positionType) {
				$positionType->count = ArrayHelper::getValue($countersArray, $positionType->id, 0);
			}

			return $allPositionTypes;
		});
	}

	/**
	 * Строит срез по статусам вакансий, демо-прототип
	 * @return RefVacancyStatuses[]
	 * Нужно заморочиться и переписать это на голый SQL, но у меня не хватает мозгов, поэтому обхожусь кешированием
	 */
	public function getGroupVacancyStatusData():array {
		$id = $this->id;
		return Yii::$app->cache->getOrSet(static::class."getGroupVacancyTypeData{$id}", static function() use ($id) {
			$allVacancyStatuses = RefVacancyStatuses::find()->active()->all();//Все справочники
			$vacancyStatusesCount = [];
			$vacancyStats = RefVacancyStatuses::find()->select(['ref_vacancy_statuses.id', 'count(ref_vacancy_statuses.id) as `count`'])//только использованные в указанной группе
			->joinWith(['relGroups'], false)
				->groupBy(['ref_vacancy_statuses.id'])
				->where(['sys_groups.id' => $id])
				->asArray()
				->all();

			foreach ($vacancyStats as $key => $value) {
				$vacancyStatusesCount[ArrayHelper::getValue($value, 'id')] = ArrayHelper::getValue($value, 'count');
			}

			foreach ($allVacancyStatuses as $vacancyStatus) {
				$vacancyStatus->count = ArrayHelper::getValue($vacancyStatusesCount, $vacancyStatus->id, 0);
			}

			return $allVacancyStatuses;
		});
	}

	/**
	 * Возвращает статистику по количеству юзеров в указанных группах (уники и summary)
	 * @param int[] $scope
	 * @return int[]
	 */
	public static function getGroupScopeUsersCount(array $scope):array {
		return Users::find()->leftJoin('rel_users_groups', 'rel_users_groups.user_id = sys_users.id')//поскольку нам нужно получать два разных агрегатора и нельзя получать индекс, то мы не можем использовать joinWith (ORM будет требовать индекс).
		->select(['COUNT(DISTINCT sys_users.id) AS dcount', 'COUNT(sys_users.id) as count'])
			->where(['rel_users_groups.group_id' => $scope])
			->asArray()
			->all();
	}

	/**
	 * Возвращает статистику по количеству вакансий в указанных группах
	 * @param int[] $scope
	 * @return int
	 */
	public static function getGroupScopeVacancyCount(array $scope):int {
		return Vacancy::find()->joinWith(['relGroups'])->where(['sys_groups.id' => $scope])->countFromCache();
	}

	/**
	 * Агрегатор статистики по количеству типов групп в скоупе
	 * @param array $scope
	 * @return array
	 */
	public static function getGroupScopeTypesData(array $scope):array {
		return RefGroupTypes::find()
			->leftJoin('sys_groups', 'sys_groups.type = ref_group_types.id')
			->where(['sys_groups.id' => $scope])->active()
			->select(['COUNT(ref_group_types.name) as count', 'ref_group_types.name as name', 'ref_group_types.id as id'])
			->groupBy('name, id')
			->asArray()
			->all();
	}

}
