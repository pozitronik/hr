<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\components\pozitronik\core\models\lcquery\LCQuery;
use app\components\pozitronik\core\traits\ARExtended;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\relations\RelUserPositionsTypes;
use app\components\pozitronik\core\interfaces\access\UserRightInterface;
use app\components\pozitronik\helpers\ArrayHelper;
use app\components\pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;
use app\components\pozitronik\core\models\core_module\PluginTrait;
use app\components\pozitronik\core\traits\Upload;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\models\relations\RelUsersAttributesTypes;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\history\models\HistoryEventInterface;
use app\modules\salary\models\traits\UsersSalaryTrait;
use app\modules\users\models\references\RefUserRoles;
use app\modules\privileges\models\relations\RelUsersPrivileges;
use app\modules\privileges\models\Privileges;
use app\modules\salary\models\references\RefUserPositions;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use Throwable;
use Yii;

/**
 * This is the model class for table "sys_users".
 *
 * @property int $id
 * @property string $username Отображаемое имя пользователя
 * @property string $login Логин
 * @property string $password Хеш пароля
 * @property string $salt Unique random salt hash
 * @property string $email email
 * @property string $comment Служебный комментарий пользователя
 * @property string $create_date Дата регистрации
 * @property string $profile_image Название файла фотографии профиля
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property bool $deleted Флаг удаления
 * @property-write string $update_password Свойство только для обновления пароля
 *
 * @property int|null $position Должность/позиция
 *
 * @property-read string $authKey
 *
 * ***************************
 *
 * ***************************
 * @property-read string $avatar
 * @property-read string $personal_number
 * @property-read string $phone
 * @property-read string $positionName
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups
 *
 *
 * @property ActiveQuery|RefUserPositions $relUserPosition Релейшен к должностям пользователей (у пользователя может быть только одна должность)
 * @property-read ActiveQuery|RelRefUserPositionsTypes[] $relRefUserPositionsTypes ID типов должностей пользователя, полученные через привязку типов к занимаемой должности
 * @property-read ActiveQuery|RefUserPositionTypes[] $refUserPositionTypes Типы должностей пользователя, полученные через привязку типов к занимаемой должности. НЕЛЬЗЯ ИСПОЛЬЗОВАТЬ КАК ГЕТТЕР, вызывать только функцию
 * @property ActiveQuery|RefUserPositionTypes[]|int $relRefUserPositionsTypesOwn Типы должностей пользователя, полученные через прямые определения (не зависящие от привязок должности)
 * @property-read RefUserPositionTypes[] $relRefUserPositionsTypesAny Типы должностей пользователя, полученные напрямую, или (в случае отсутствия прямых указаний) через должности (использовать при расчётах и выводе)
 * @property-read ActiveQuery|RelUserPositionsTypes[] $relUserPositionsTypes Релейшен к таблице связей пользователя с типами должностей (для прямого определения)
 *
 * @property ActiveQuery|Groups[] $relGroups
 * @property-write array $rolesInGroup
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles Релейшен к ролям пользователей в группах
 * @property int $dropGroups
 *
 * ***************************
 * Опции
 * @property Options $options
 * **************************
 * Внешние идентификаторы
 * @property UsersIdentifiers $relUsersIdentifiers
 * **************************
 * Права в системе
 * @property RelUsersPrivileges[]|ActiveQuery $relUsersPrivileges Релейшен к таблице связей с привилегиями
 * @property Privileges[]|ActiveQuery $relPrivileges Релейшен к привилегиям
 * @property int $dropUsersPrivileges Атрибут для удаления привилегий
 * @property-read UserRightInterface[] $rights Массив прав пользователя в системе, вычисляется из суммы привилегий
 * **************************
 * Атрибуты
 * @property RelUsersAttributes[]|ActiveQuery $relUsersAttributes Релейшен к таблице связей с атрибутами
 * @property int $dropUsersAttributes
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles Релейшен к ролям пользователей
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesLeader Релейшен к ролям пользователей с флагом босса
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesImportant Релейшен к ролям пользователей с флагом важной шишки
 * @property DynamicAttributes[]|ActiveQuery $relDynamicAttributes Релейшен к атрибутам
 * @property ActiveQuery|Groups[] $relLeadingGroups Группы, в которых пользователь лидер
 * @property RelUsersAttributesTypes[]|ActiveQuery $relUsersAttributesTypes Релейшен к таблице связей с типами атрибутов
 *
 * @property RefAttributesTypes[]|ActiveQuery $refAttributesTypes Типы атрибутов, присвоенных пользователю
 * *************************
 */
class Users extends ActiveRecord {
	use Upload;
	use UsersSalaryTrait;//потом сделаем этот вызов опциональным в зависимости от подключения модуля. Или нет. Пока не заботимся.
	use ARExtended;
	use PluginTrait;

	/*Переменная для инстанса заливки аватарок*/
	public $upload_image;
	public $update_password;

	public const PROFILE_IMAGE_DIRECTORY = '@app/web/profile_photos/';

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'daddy' => [self::class => 'username'],
				'position' => [RefUserPositions::class => 'name'],
				'password' => false
			],
			'relations' => [
				RelUsersGroups::class => ['id' => 'user_id'],
				RelUsersPrivileges::class => ['id' => 'user_id'],
				RelUsersAttributes::class => ['id' => 'user_id'],
				RelUsersGroupsRoles::class => function(ActiveQuery $condition, ActiveRecord $model):ActiveQuery {
					$ids = implode(',', ArrayHelper::getColumn($this->relUsersGroups, 'id'));
					if (!empty($ids)) $condition->orWhere("model = '{$model->formName()}' and (new_attributes->'$.user_group_id' in ({$ids}) or old_attributes->'$.user_group_id' in ({$ids}))");
					return $condition;
				}
			],
			'events' => [
				HistoryEventInterface::EVENT_DELETED => [
					'deleted' => [
						'from' => false,
						'to' => true
					]
				]
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_users';
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
			[['username', 'login', 'password', 'email'], 'required'],//Не ставим create_date как required, поле заполнится default-валидатором (а если нет - отвалится при инсерте в базу)
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'position'], 'integer'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['username', 'password', 'salt', 'email', 'profile_image', 'update_password'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['upload_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1048576],//Это только клиентская валидация, на сервере атрибут всегда будет валидироваться успешно
			[['relGroups', 'dropGroups', 'relDynamicAttributes', 'dropUsersAttributes', 'relPrivileges', 'dropPrivileges', 'relRefUserPositionTypes', 'relRefUserPositionsTypesOwn'], 'safe'],
			/*Мы не можем переопределить или наследовать метод в трейте, поэтому ПОКА добавляю правила валидации атрибутов из трейта сюда. Но потом нужно придумать, как разделить код*/
			[['relGrade', 'relPremiumGroup', 'relLocation'], 'safe'],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]//default-валидатор срабатывает только на незаполненные атрибуты, его нельзя использовать как обработчик любых изменений атрибута
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'username' => 'Имя пользователя',
			'login' => 'Логин',
			'password' => 'Пароль',
			'salt' => 'Unique random salt hash',
			'email' => 'Почтовый адрес',
			'comment' => 'Служебный комментарий пользователя',
			'create_date' => 'Дата регистрации',
			'daddy' => 'ID зарегистрировавшего/проверившего пользователя',
			'deleted' => 'Флаг удаления',
			'position' => 'Должность',
			'profile_image' => 'Изображение профиля',
			'upload_image' => 'Изображение профиля',
			'update_password' => 'Смена пароля',
			'relGroups' => 'Группы',
			'relPrivileges' => 'Привилегии',
			/*UsersSalaryTrait attributes*/
			'relGrade' => 'Грейд',
			'relPremiumGroup' => 'Группа премирования',
			'relLocation' => 'Расположение',
			'relSalaryFork' => 'Зарплатная вилка',
			'relRefUserPositionsTypes' => 'Тип должности',
			'relRefUserPositionsTypesOwn' => 'Тип должности (прямой)'
		];
	}

	/**
	 * @param string $login
	 * @return Users|null
	 */
	public static function findByLogin(string $login):?Users {
		return self::findOne(['login' => $login]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeValidate():bool {
		if ($this->isNewRecord) {
			if (null === $this->salt) {
				$this->salt = sha1(uniqid((string)mt_rand(), true));
				$this->password = sha1($this->password.$this->salt);
			}
		} elseif (!empty($this->update_password)) {
			$this->salt = sha1(uniqid((string)mt_rand(), true));
			$this->password = sha1($this->update_password.$this->salt);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return string
	 */
	public function getAuthKey():string {
		return md5($this->id.md5($this->login));
	}

	/*Прототипирование*/

	/**
	 * @return string
	 */
	public function getAvatar():string {
		$profile_image = $this->profile_image??$this->id.".png";
		return is_file(Yii::getAlias(self::PROFILE_IMAGE_DIRECTORY.$profile_image))?"/profile_photos/{$profile_image}":"/img/avatar.png";
	}

	/**
	 * @return null|string
	 */
	public function getPersonal_number():?string {
		return null;
	}

	/**
	 * @return null|string
	 */
	public function getPhone():?string {
		return null;
	}

	/**
	 * @return ActiveQuery|RelUsersGroups[]
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['user_id' => 'id']);
	}

	/**
	 * @return Groups[]|ActiveQuery|LCQuery
	 */
	public function getRelGroups() {
		return $this->hasMany(Groups::class, ['id' => 'group_id'])->via('relUsersGroups');
	}

	/**
	 * Релейшен к назначению ролей в этой группе
	 * @return ActiveQuery|RelUsersGroupsRoles[]
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['user_group_id' => 'id'])->via('relUsersGroups');
	}

	/**
	 * Все назначенные роли этого пользователя (используется изначально для определения руководителя)
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles');
	}

	/**
	 * Все роли этого пользователя с флагом лидера
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRolesLeader() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles')->where(['ref_user_roles.boss_flag' => true]);
	}

	/**
	 * Все роли этого пользователя с флагом важной шишки
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRolesImportant() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles')->where(['ref_user_roles.importance_flag' => true]);
	}

	/**
	 * @param array $relUsersGroups
	 * @throws Throwable
	 */
	public function setRelGroups(array $relUsersGroups):void {
		RelUsersGroups::linkModels($this, $relUsersGroups);
	}

	/**
	 * @return int
	 */
	public function getDropGroups():array {
		return [];
	}

	/**
	 * @param int $dropGroups
	 * @throws Throwable
	 */
	public function setDropGroups(array $dropGroups):void {
		RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $dropGroups, 'user_id' => $this->id])->select('id')]);
		RelUsersGroups::unlinkModels($this, $dropGroups);
	}

	/**
	 * @prototype
	 * @param $access
	 * @return bool
	 */
	public function is($access):bool {
		/** @noinspection DegradedSwitchInspection */
		switch ($access) {
			case 'sysadmin':
				return 1 === CurrentUser::Id();
			break;
			default:
				return null !== $access;
			break;
		}
	}

	/**
	 * Добавляет массив ролей пользователя к группе
	 * @param array<int, array<int>> $groupRoles
	 * @throws Throwable
	 */
	public function setRolesInGroup(array $groupRoles):void {
		foreach ($groupRoles as $group => $roles) {
			RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $group, 'user_id' => $this->id])->select('id')]);
			/** @var int $roles */
			foreach ($roles as $role) {
				RelUsersGroupsRoles::setRoleInGroup($role, $group, $this->id);
			}
		}
	}

	/**
	 * @return string|null
	 * @throws Throwable
	 * @deprecated
	 */
	public function getPositionName():?string {
		return ArrayHelper::getValue($this->relRefUserPositions, 'name');
	}

	/**
	 * @return Options
	 */
	public function getOptions():Options {
		return new Options(['userId' => $this->id]);
	}

	/**
	 * Пытается подгрузить файл картинки, если он есть
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public function uploadAvatar():bool {
		if (null !== $imageFile = $this->uploadFile(self::PROFILE_IMAGE_DIRECTORY, (string)$this->id, null, 'upload_image', PATHINFO_BASENAME)) {
			$this->setAndSaveAttribute('profile_image', $imageFile);
			return true;
		}
		return false;
	}

	/**
	 * @return RelUsersAttributes[]|ActiveQuery
	 */
	public function getRelUsersAttributes() {
		return $this->hasMany(RelUsersAttributes::class, ['user_id' => 'id']);
	}

	/**
	 * @return DynamicAttributes[]|ActiveQuery|LCQuery
	 */
	public function getRelDynamicAttributes() {
		return $this->hasMany(DynamicAttributes::class, ['id' => 'attribute_id'])->via('relUsersAttributes');
	}

	/**
	 * @param ActiveQuery|DynamicAttributes[] $relDynamicAttributes
	 * @throws Throwable
	 */
	public function setRelDynamicAttributes(ActiveQuery|array $relDynamicAttributes):void {
		RelUsersAttributes::linkModels($this, $relDynamicAttributes);
	}

	/**
	 * @param int $dropUsersAttributes
	 * @throws Throwable
	 */
	public function setDropUsersAttributes(array $dropUsersAttributes):void {
		/*Сами значения атрибутов сохранятся в базе и должны будут восстановиться, если атрибут присвоить пользователю обратно*/
		RelUsersAttributes::unlinkModels($this, $dropUsersAttributes);
	}

	/**
	 * @return int
	 */
	public function getDropUsersAttributes():array {
		return [];
	}

	/**
	 * Вернёт массив всех пользователей с галочкой лидеров
	 * @return array
	 */
	public static function mapLeaders():array {
//		return Yii::$app->cache->getOrSet(static::class."MapLeaders", function() {
		$data = self::find()->joinWith(['relRefUserRolesLeader'])->all();
		return ArrayHelper::map($data, 'id', 'username');
//		});
	}

	/**
	 * Вернёт все группы, в которых пользователь имеет галочку босса
	 * @return LCQuery|ActiveQuery
	 */
	public function getRelLeadingGroups() {
		return $this->getRelGroups()->joinWith(['relRefUserRolesLeader']);
	}

	/**
	 * @return UserRightInterface[]
	 * @prototype todo
	 */
	public function getRights():array {
		$rights = array_merge([[]], ArrayHelper::getColumn($this->relPrivileges, 'userRights'), ArrayHelper::getColumn(Privileges::GetDefaultPrivileges(), 'userRights'));
		return array_merge(...$rights);
	}

	/**
	 * @return RelUsersPrivileges[]|ActiveQuery
	 */
	public function getRelUsersPrivileges() {
		return $this->hasMany(RelUsersPrivileges::class, ['user_id' => 'id']);
	}

	/**
	 * @return Privileges[]|ActiveQuery
	 */
	public function getRelPrivileges() {
		return $this->hasMany(Privileges::class, ['id' => 'privilege_id'])->via('relUsersPrivileges');
	}

	/**
	 * Кривоватый метод работы с привлегиями; пока оставляю так, после рефакторинга админки привлегии будут редаткироваться или аяксом, или просто отдельно, соответственно будет юзаться удаление отдельным атрибутом
	 * @param ActiveQuery|Privileges[] $relPrivileges
	 * @throws Throwable
	 */
	public function setRelPrivileges(ActiveQuery|array $relPrivileges):void {
		/*Чтобы не захламлять лог пересозданием, находим только реально удаялемые записи. */
		$currentPrivilegesId = ArrayHelper::getColumn($this->relPrivileges, 'id');
		$droppedPrivileges = array_diff($currentPrivilegesId, (array)$relPrivileges);
		RelUsersPrivileges::unlinkModels($this, $droppedPrivileges);
		RelUsersPrivileges::linkModels($this, $relPrivileges);
	}

	/**
	 * Отдельный атрибут, если нужно будет удалять через аякс, например
	 * @param int[] $dropUsersPrivileges
	 * @throws Throwable
	 */
	public function setDropUsersPrivileges(array $dropUsersPrivileges):void {
		RelUsersPrivileges::unlinkModels($this, $dropUsersPrivileges);
	}

	/**
	 * @return int
	 */
	public function getDropUsersPrivileges():array {
		return [];
	}

	/**
	 * @return RelUsersAttributesTypes[]|ActiveQuery
	 */
	public function getRelUsersAttributesTypes() {
		return $this->hasMany(RelUsersAttributesTypes::class, ['user_attribute_id' => 'id'])->via('relUsersAttributes');
	}

	/**
	 * @return RefAttributesTypes[]|ActiveQuery
	 */
	public function getRefAttributesTypes() {
		return $this->hasMany(RefAttributesTypes::class, ['id' => 'type'])->via('relUsersAttributesTypes');
	}

	/****************************************
	 * Блок работы с должностями и их типами.
	 * Есть должности, у должностей есть типы (бизнес, ит, etc). Если пользователю назначена должность, то тип его должности определяется через эти связи.
	 * Однако, пользователю можно напрямую выставить тип должности, тогда во всех расчётах используется он, а не привязанный к должности.
	 * Должности лежат в RefUserPositions, типы должностей - в RefUserPositionTypes.
	 * getRelRefUserPositionsTypes отдаёт типы должностей, привязанные к должности.
	 * getRelRefUserPositionsTypesOwn отдаёт типы должностей, привязанные к пользователю.
	 * getRelRefUserPositionsTypesAny отдаёт типы должностей, используемые всегда
	 */

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRelUserPosition() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position']);
	}

	/**
	 * ID типов должностей пользователя, полученные через привязку типов к занимаемой должности
	 * @return RelRefUserPositionsTypes[]|ActiveQuery
	 */
	public function getRelRefUserPositionsTypes() {
		return $this->hasMany(RelRefUserPositionsTypes::class, ['position_id' => 'position']);
	}

	/**
	 * Типы должностей пользователя, полученные через привязку типов к занимаемой должности
	 * @return ActiveQuery|RefUserPositionTypes
	 */
	public function getRefUserPositionTypes() {
		return $this->hasOne(RefUserPositionTypes::class, ['id' => 'position_type_id'])->via('relRefUserPositionsTypes');
	}

	/**
	 * ID типов должностей, полученных через переопределения (не зависящие от привязок должности)
	 * @return RelUserPositionsTypes[]|ActiveQuery
	 */
	public function getRelUserPositionsTypes() {
		return $this->hasMany(RelUserPositionsTypes::class, ['user_id' => 'id']);
	}

	/**
	 * Типы должностей пользователя, полученные через переопределения (не зависящие от привязок должности)
	 * @return ActiveQuery|RefUserPositionTypes[]
	 */
	public function getRelRefUserPositionsTypesOwn() {
		return $this->hasMany(RefUserPositionTypes::class, ['id' => 'position_type_id'])->via('relUserPositionsTypes');
	}

	/**
	 * Сюда прилетают изменения типа должности из профиля пользователя. Мы не меняем тип должности у самой должности, внося измненения в таблицу переопределний для этого конкретного юзернейма
	 * @param int $relRefUserPositionTypes
	 * @throws Throwable
	 */
	public function setRelRefUserPositionsTypesOwn(array $relRefUserPositionTypes):void {
//		if ([] === array_diff($this->relUserPosition->types, $relRefUserPositionTypes) && empty($this->relUserPositionsTypes)) return;//это не изменение, пришли типы, определённые должностью

		/*Чтобы не захламлять лог пересозданием, находим только реально удаляемые записи. */
		$currentUserPositionTypesId = ArrayHelper::getColumn($this->relUserPositionsTypes, 'position_type_id');
		$droppedUserPositionTypes = array_diff($currentUserPositionTypesId, (array)$relRefUserPositionTypes);
		RelUserPositionsTypes::unlinkModels($this, $droppedUserPositionTypes);
		RelUserPositionsTypes::linkModels($this, $relRefUserPositionTypes);
	}

	/**
	 * Типы должностей пользователя, полученные напрямую, или (в случае отсутствия прямых указаний) через должности
	 * @return RefUserPositionTypes[]
	 */
	public function getRelRefUserPositionsTypesAny():array {
		return Yii::$app->cache->getOrSet("getRelRefUserPositionsTypesAny{$this->id}", function() {
			if ([] === $result = $this->getRelRefUserPositionsTypesOwn()->all()) $result = $this->getRefUserPositionTypes()->all();
			return $result;
		});

	}

	/**************/

	/**
	 * Возвращает список руководителей пользователя (пока только на уровень выше)
	 * @return Users[]
	 * Можно ускорить, переписав на выборку. Пока оставлено так, до выяснения необходимости.
	 */
	public function getBosses():array {

		$result = [[]];
		/** @var Groups $group */
		foreach ((array)$this->relGroups as $group) {
			if ($group->isLeader($this)) {
				/** @var Groups $parentGroup */
				foreach ((array)$group->relParentGroups as $parentGroup) {
					$result[] = $parentGroup->leaders;
				}
			} else {
				$result[] = $group->leaders;
			}
		}
		$result = array_merge(...$result);
		$uniqueKeys = [];
		$uniqueUsers = [];
		array_walk($result, static function($value, &$key) use (&$uniqueKeys, &$uniqueUsers) {
			/** @var Users $value */
			if (!in_array($value->id, $uniqueKeys)) {
				$uniqueUsers[] = $value;
				$uniqueKeys[] = $value->id;
			}
		});
		return $uniqueUsers;
	}

	/********************************************************/

	/**
	 * @return UsersIdentifiers|ActiveQuery
	 */
	public function getRelUsersIdentifiers() {
		return $this->hasOne(UsersIdentifiers::class, ['user_id' => 'id']);
	}

}
