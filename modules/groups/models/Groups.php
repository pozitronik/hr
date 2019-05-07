<?php
declare(strict_types = 1);

namespace app\modules\groups\models;

use pozitronik\helpers\ArrayHelper;
use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\core\core_module\PluginTrait;
use app\models\core\LCQuery;
use app\models\core\traits\Upload;
use app\modules\groups\models\traits\Graph;
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
 * @property integer $type Тип группы
 * @property string $comment Описание
 * @property integer|null $daddy Пользователь, создавший группу
 * @property string $logotype Название файла-логотипа
 * @property ActiveQuery|Users[] $relUsers Пользователи в группе
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Связь с релейшеном пользователей
 * @property ActiveQuery|Groups[] $relChildGroups Группы, дочерние по отношению к текущей
 * @property-write array $dropChildGroups Свойство для передачи массива отлинкуемых дочерних групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsParent Релейшен групп для получения дочерних групп
 * @property array $dropParentGroups Свойство для передачи массива отлинкуемых родительских групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsChild Релейшен групп для получения родительских групп
 * @property ActiveQuery|Groups[] $relParentGroups Группы, родительские по отношению к текущей
 * @property ActiveQuery|RefGroupTypes $relGroupTypes Тип группы через релейшен
 *
 * @property-read Users[] $leaders Пользюки, прописанне в группе с релейшеном лидера (владелец/руководитель)
 * @property-read Users|null $leader Один пользователь из лидеров (для презентации)
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesLeader
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles
 * @property array $rolesInGroup
 * @property array $dropUsers
 * @property boolean $deleted
 * @property LCQuery $relUsersHierarchy Пользователи во всех группах вниз по иерархии
 * @property-read string $logo Полный путь к логотипу/дефолтной картинке
 *
 * @property-read integer $usersCount Количество пользователей в группе
 *
 * @property-read integer $childGroupsCount Количество подгрупп (следующего уровня)
 *
 */
class Groups extends ActiveRecordExtended {
	use Graph;
	use Upload;
	use PluginTrait;

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
	 * @return ActiveQuery|RelUsersGroups[]
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['group_id' => 'id']);
	}

	/**
	 * @return ActiveQuery|Users[]|LCQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
	}

	/**
	 * @param ActiveQuery|Users[] $relGroupsUsers
	 * @throws Throwable
	 */
	public function setRelUsers($relGroupsUsers):void {
		RelUsersGroups::linkModels($relGroupsUsers, $this);
	}

	/**
	 * @param array $dropUsers
	 * @throws Throwable
	 */
	public function setDropUsers(array $dropUsers):void {
		RelUsersGroupsRoles::deleteAllEx(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $dropUsers])->select('id')]);
		RelUsersGroups::unlinkModels($dropUsers, $this);
	}

	/**
	 * Релейшен к назначению ролей в этой группе
	 * @return ActiveQuery|RelUsersGroupsRoles[]
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['user_group_id' => 'id'])->via('relUsersGroups');
	}

	/**
	 * Все назначенные роли в этой группе
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles');
	}

	/**
	 * Все роли боссов в этой группе
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRolesLeader() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles')->where(['ref_user_roles.boss_flag' => true]);
	}

	/**
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsChild() {
		return $this->hasMany(RelGroupsGroups::class, ['parent_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery|LCQuery
	 */
	public function getRelChildGroups() {
		return $this->hasMany(self::class, ['id' => 'child_id'])->via('relGroupsGroupsChild');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param ActiveQuery|Groups[] $childGroups
	 * @throws Throwable
	 */
	public function setRelChildGroups($childGroups):void {
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
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsParent() {
		return $this->hasMany(RelGroupsGroups::class, ['child_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery|LCQuery
	 */
	public function getRelParentGroups() {
		return $this->hasMany(self::class, ['id' => 'parent_id'])->via('relGroupsGroupsParent');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param ActiveQuery|Groups[] $parentGroups
	 * @throws Throwable
	 */
	public function setRelParentGroups($parentGroups):void {
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
	 * @return RefGroupTypes|ActiveQuery
	 */
	public function getRelGroupTypes() {
		return $this->hasOne(RefGroupTypes::class, ['id' => 'type']);
	}

	/**
	 * Вернёт всех пользователей в группе с меткой босса
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getLeaders():array {
		return $this->getRelUsers()->joinWith(['relRefUserRolesLeader'])->where(['rel_users_groups.group_id' => $this->id])->all();
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
	 * @param array<integer, array<integer>> $userRoles
	 * @throws Throwable
	 */
	public function setRolesInGroup(array $userRoles):void {
		foreach ($userRoles as $user => $roles) {
			$currentUserGroupId = RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $user])->select('id')->one();
			$currentUserRoles = RelUsersGroupsRoles::find()->where(['user_group_id' => $currentUserGroupId])->all();
			$currentUserRolesId = ArrayHelper::getColumn($currentUserRoles, 'role');
			$deletedRolesId = array_diff($currentUserRolesId, $roles);//id удаляемых ролей
			/*Сначала удаляем роли, которых нет в обновлённом списке*/
			RelUsersGroupsRoles::deleteAllEx(['user_group_id' => $currentUserGroupId, 'role' => $deletedRolesId]);
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
		return is_file(Yii::getAlias(self::LOGO_IMAGE_DIRECTORY.$this->logotype))?"/group_logotypes/{$this->logotype}":"/img/group_logo.jpg";
	}

	/**
	 * Собираем рекурсивно айдишники всех групп вниз по иерархии
	 * @param int|null $initialId Параметр для учёта рекурсии
	 * @return array<int>
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
					'data-typecolor' => ArrayHelper::getValue($item->relGroupTypes, 'color')
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
	public function getChildGroupsCount():int {
		return (int)$this->getRelChildGroups()->count();
	}

	/**
	 * Удаляет все кеши, связанные с группой
	 */
	private function dropCaches():void {
		Yii::$app->cache->delete(static::class."CollectRecursiveIds".$this->id);
		Yii::$app->cache->delete(static::class."DataOptions");
	}

	/**
	 * Строит дерево иерархии id групп с учётом рекурсии
	 * @param array $stackedId Массив всех обойдённых групп (плоский)
	 * @return array Массив всех обойдённых групп (иерархический)
	 */
	public function buildHierarchyTree(&$stackedId = []):array {
		if (!in_array($this->id, $stackedId)) $stackedId[] = $this->id;
		$hierarchyTree = [];
		/** @var self[] $childGroups */
		$childGroups = $this->getRelChildGroups()->orderBy('name')->active()->all();
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

}
