<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\ActiveRecordExtended;
use app\models\core\StrictInterface;
use app\models\core\traits\Upload;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\models\relations\RelUsersAttributesTypes;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\core\LCQuery;
use app\modules\salary\models\traits\UsersSalaryTrait;
use app\modules\users\models\references\RefUserRoles;
use app\modules\privileges\models\relations\RelUsersPrivileges;
use app\modules\privileges\models\Privileges;
use app\modules\privileges\models\UserRightInterface;
use app\widgets\alert\AlertModel;
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
use yii\db\ActiveRecord;

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
 * @property boolean $deleted Флаг удаления
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
 * @property ActiveQuery|RefUserPositions $relUserPositions Релейшен к должностям пользователей
 *
 *
 * @property ActiveQuery|Groups[] $relGroups
 * @property-write array $rolesInGroup
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles Релейшен к ролям пользователей в группах
 * @property integer[] $dropGroups
 *
 * ***************************
 * Опции
 * @property Options $options
 * **************************
 * Права в системе
 * @property RelUsersPrivileges[]|ActiveQuery $relUsersPrivileges Релейшен к таблице связей с привилегиями
 * @property Privileges[]|ActiveQuery $relPrivileges Релейшен к привилегиям
 * @property integer[] $dropUsersPrivileges Атрибут для удаления привилегий
 * @property-read UserRightInterface[] $rights Массив прав пользователя в системе, вычисляется из суммы привилегий
 * **************************
 * Атрибуты
 * @property RelUsersAttributes[]|ActiveQuery $relUsersAttributes Релейшен к таблице связей с атрибутами
 * @property integer[] $dropUsersAttributes
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles Релейшен к ролям пользователей
 * @property ActiveQuery|RefUserRoles[] $relRefUserRolesLeader Релейшен к ролям пользователей с флагом босса
 * @property DynamicAttributes[]|ActiveQuery $relDynamicAttributes Релейшен к атрибутам
 * @property ActiveQuery|Groups[] $relLeadingGroups Группы, в которых пользователь лидер
 * @property RelUsersAttributesTypes[]|ActiveQuery $relUsersAttributesTypes Релейшен к таблице связей с типами атрибутов
 * @property RefAttributesTypes[]|ActiveQuery $refAttributesTypes Типы атрибутов, присвоенных пользователю
 */
class Users extends ActiveRecordExtended implements StrictInterface {
	use Upload;
	use UsersSalaryTrait;//потом сделаем этот вызов опциональным в зависимости от подключения модуля. Или нет. Пока не заботимся.

	/*Переменная для инстанса заливки аватарок*/
	public $upload_image;

	public const PROFILE_IMAGE_DIRECTORY = '@app/web/profile_photos/';

	/**
	 * {@inheritDoc}
	 */
	public function historyRules():array {
		return [
			'attributes' => [
				'daddy' => [self::class => 'username'],
				'position' => [RefUserPositions::class => 'name'],
				'password' => false,
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
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['username', 'login', 'password', 'email', 'create_date'], 'required'],
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'position'], 'integer'],
			[['deleted'], 'boolean'],
			[['deleted'], 'default', 'value' => false],
			[['username', 'password', 'salt', 'email', 'profile_image'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['upload_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1048576],//Это только клиентская валидация, на сервере атрибут всегда будет валидироваться успешно
			[['relGroups', 'dropGroups', 'relDynamicAttributes', 'dropUsersAttributes', 'relPrivileges', 'dropPrivileges'], 'safe'],
			/*Мы не можем переопределить или наследовать метод в трейте, поэтому ПОКА добавляю правила валидации атрибутов из трейта сюда. Но потом нужно придумать, как разделить код*/
			[['relGrade', 'relPremiumGroup', 'relLocation'], 'safe']
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
			'relSalaryFork' => 'Зарплатная вилка'
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
	 * Солим пароль
	 */
	public function applySalt():void {
		$this->salt = sha1(uniqid((string)mt_rand(), true));
		$this->password = sha1($this->password.$this->salt);
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 * @throws Throwable
	 */
	public function createModel(?array $paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			if (null === $this->salt) $this->applySalt();

			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					AlertModel::SuccessNotify();
					$this->refresh();
					return true;
				}
				AlertModel::ErrorsNotify($this->errors);
			}
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 * @throws Throwable
	 */
	public function updateModel(?array $paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if (!empty($newPassword = ArrayHelper::getValue($paramsArray, 'update_password', false))) {
				$this->password = $newPassword;
				$this->applySalt();
			}

			if ($this->save()) {
				AlertModel::SuccessNotify();
				$this->refresh();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
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
		return is_file(Yii::getAlias(self::PROFILE_IMAGE_DIRECTORY.$profile_image))?"/profile_photos/{$profile_image}":"/img/avatar.jpg";
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
	 * @param array $relUsersGroups
	 * @throws Throwable
	 */
	public function setRelGroups($relUsersGroups):void {
		RelUsersGroups::linkModels($this, $relUsersGroups);
	}

	/**
	 * @return integer[]
	 */
	public function getDropGroups():array {
		return [];
	}

	/**
	 * @param integer[] $dropGroups
	 * @throws Throwable
	 */
	public function setDropGroups($dropGroups):void {
		RelUsersGroupsRoles::deleteAllEx(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $dropGroups, 'user_id' => $this->id])->select('id')]);
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
	 * @param array<integer, array<integer>> $groupRoles
	 * @throws Throwable
	 */
	public function setRolesInGroup(array $groupRoles):void {
		foreach ($groupRoles as $group => $roles) {
			RelUsersGroupsRoles::deleteAllEx(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $group, 'user_id' => $this->id])->select('id')]);
			/** @var integer[] $roles */
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
	 * @param DynamicAttributes[]|ActiveQuery $relDynamicAttributes
	 * @throws Throwable
	 */
	public function setRelDynamicAttributes($relDynamicAttributes):void {
		RelUsersAttributes::linkModels($this, $relDynamicAttributes);
	}

	/**
	 * @param integer[] $dropUsersAttributes
	 * @throws Throwable
	 */
	public function setDropUsersAttributes($dropUsersAttributes):void {
		/*Сами значения атрибутов сохранятся в базе и должны будут восстановиться, если атрибут присвоить пользователю обратно*/
		RelUsersAttributes::unlinkModels($this, $dropUsersAttributes);
	}

	/**
	 * @return integer[]
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
		return array_unique(array_merge(...$rights));
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
	 * @param Privileges[]|ActiveQuery $relPrivileges
	 * @throws Throwable
	 */
	public function setRelPrivileges($relPrivileges):void {
		RelUsersPrivileges::unlinkModels($this, $this->relPrivileges);
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
	 * @return integer[]
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

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRelUserPositions() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position']);
	}
}
