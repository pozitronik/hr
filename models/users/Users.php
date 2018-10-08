<?php
declare(strict_types = 1);

namespace app\models\users;

use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\user\CurrentUser;
use app\models\workgroups\Workgroups;
use yii\db\ActiveRecord;
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
 * @property int $deleted Флаг удаления
 *
 * @property-read string $authKey
 *
 * ***************************
 *
 * ***************************
 * @property-read string $avatar
 * @property-read string $personal_number
 * @property-read string $phone
 * @property-read Workgroups[] $workgroups
 */
class Users extends ActiveRecord {
	use ARExtended;

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
			[['daddy', 'deleted'], 'integer'],
			[['username', 'password', 'salt', 'email', 'profile_image'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'username' => 'Отображаемое имя пользователя',
			'login' => 'Логин',
			'password' => 'Пароль',
			'salt' => 'Unique random salt hash',
			'email' => 'email',
			'comment' => 'Служебный комментарий пользователя',
			'create_date' => 'Дата регистрации',
			'daddy' => 'ID зарегистрировавшего/проверившего пользователя',
			'deleted' => 'Флаг удаления'
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
		if ($this->loadArray($paramsArray)) {
			if (null === $this->salt) $this->applySalt();

			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			return $this->save();
		}
		return false;
	}

	/**
	 * @param $paramsArray
	 * @return bool
	 */
	public function updateUser($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if (null === $this->salt) $this->applySalt();
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
		if (null === $this->profile_image) $this->profile_image = $this->id.".png";
		return file_exists(Yii::getAlias(self::PROFILE_IMAGE_DIRECTORY.$this->profile_image))?"/profile_photos/{$this->profile_image}":"/img/avatar.jpg";
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
	 * @return Workgroups[]
	 */
	public function getWorkgroups():array {
		return [
			new Workgroups([
				'id' => 1,
				'name' => 'Пятничные алкаши',
				'comment' => 'Каждый день - праздник'
			]),
			new Workgroups([
				'id' => 2,
				'name' => 'Братство ножа и топора',
				'comment' => 'Несите ваши денежки'
			]),
			new Workgroups([
				'id' => 3,
				'name' => 'Разработчики',
				'comment' => 'Кто пишет софт? Мы пишем софт.'
			])

		];
	}

}
