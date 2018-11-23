<?php
declare(strict_types = 1);

namespace app\models\users;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\competencies\Competencies;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\references\refs\RefUserPositions;
use app\models\relations\RelUsersCompetencies;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\models\user\CurrentUser;
use app\models\groups\Groups;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Throwable;
use Yii;
use yii\web\UploadedFile;

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
 * @property int $deleted Флаг удаления
 *
 * @property integer|null $position Должность/позиция
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
 * @property ActiveQuery|Groups[] $relGroups
 * @property-write array $rolesInGroup
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles Релейшен к ролям пользователей в группах
 * @property-write integer[] $dropGroups
 *
 * ***************************
 * Опции
 * ***************************
 * @property Options $options
 * **************************
 * Компетенции
 * **************************
 * @property RelUsersCompetencies[]|ActiveQuery $relUsersCompetencies Релейшен к таблице связей с компетенциям
 * @property integer[] $dropCompetencies
 * @property Competencies[]|ActiveQuery $relCompetencies Релейшен к компетенциям
 */
class Users extends ActiveRecord {
	use ARExtended;
	/*Переменная для инстанса заливки аватарок*/
	public $upload_image;

	public const PROFILE_IMAGE_DIRECTORY = '@app/web/profile_photos/';

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
			[['username', 'login', 'password', 'salt', 'email', 'create_date'], 'required'],
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'deleted', 'position'], 'integer'],
			[['username', 'password', 'salt', 'email', 'profile_image'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['upload_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1048576],
			[['relGroups', 'dropGroups', 'relCompetencies', 'dropCompetencies'], 'safe']
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
			'update_password' => 'Смена пароля'
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
	 * @param $paramsArray
	 * @return bool
	 * @throws Throwable
	 */
	public function createUser($paramsArray):bool {
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
					return true;
				}
			}
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param $paramsArray
	 * @return bool
	 * @throws Throwable
	 */
	public function updateUser($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if (!empty($newPassword = ArrayHelper::getValue($paramsArray, 'update_password', false))) {
				$this->password = $newPassword;
				$this->applySalt();
			}

			return $this->save();
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
	 * @return Groups[]|ActiveQuery
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
	 * @param array $relUsersGroups
	 * @throws Throwable
	 */
	public function setRelGroups($relUsersGroups):void {
		RelUsersGroups::linkModels($this, $relUsersGroups);
	}

	/**
	 * @param integer[] $dropGroups
	 * @throws Throwable
	 */
	public function setDropGroups(array $dropGroups):void {
		RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $dropGroups, 'user_id' => $this->id])->select('id')]);
		RelUsersGroups::unlinkModels($this, $dropGroups);
	}

	/**
	 * prototype
	 * @param $access
	 * @return bool
	 */
	public function is($access):bool {
		return null !== $access;
	}

	/**
	 * @return RefUserPositions|ActiveQuery
	 */
	public function getRelUserPositions() {
		return $this->hasOne(RefUserPositions::class, ['id' => 'position']);
	}

	/**
	 * Добавляет массив ролей пользователя к группе
	 * @param array<integer, array<integer>> $groupRoles
	 */
	public function setRolesInGroup(array $groupRoles):void {
		foreach ($groupRoles as $group => $roles) {
			RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $group, 'user_id' => $this->id])->select('id')]);
			/** @var integer[] $roles */
			foreach ($roles as $role) {
				RelUsersGroupsRoles::setRoleInGroup($role, $group, $this->id);
			}
		}
	}

	/**
	 * @return string|null
	 */
	public function getPositionName():?string {
		return $this->relUserPositions?$this->relUserPositions->name:'<Не назначен на должность>';
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
	 */
	public function uploadAvatar():bool {
		$uploadedFile = UploadedFile::getInstance($this, 'upload_image');
		if ($uploadedFile && $this->validate('upload_image') && $uploadedFile->saveAs(Yii::getAlias(self::PROFILE_IMAGE_DIRECTORY."/{$this->id}.{$uploadedFile->extension}"), false)) {
			$this->setAndSaveAttribute('profile_image', "{$this->id}.{$uploadedFile->extension}");
			return true;
		}
		return false;
	}

	/**
	 * @return RelUsersCompetencies[]|ActiveQuery
	 */
	public function getRelUsersCompetencies() {
		return $this->hasMany(RelUsersCompetencies::class, ['user_id' => 'id']);
	}

	/**
	 * @return Competencies[]|ActiveQuery
	 */
	public function getRelCompetencies() {
		return $this->hasMany(Competencies::class, ['id' => 'competency_id'])->via('relUsersCompetencies');
	}

	/**
	 * @param Competencies[]|ActiveQuery $relCompetencies
	 * @throws Throwable
	 */
	public function setRelCompetencies($relCompetencies):void {
		RelUsersCompetencies::linkModels($this, $relCompetencies);
	}

	/**
	 * @param integer[] $dropCompetencies
	 * @throws Throwable
	 */
	public function setDropCompetencies(array $dropCompetencies):void {
		/*Сами значения компетенций сохранятся в базе и должны будут восстановиться, если компетенцию присвоить пользователю обратно*/
		RelUsersCompetencies::unlinkModels($this, $dropCompetencies);
	}

}
