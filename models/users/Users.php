<?php
declare(strict_types=1);

namespace app\models\users;

use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\user\CurrentUser;
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
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property int $deleted Флаг удаления
 */
class Users extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string {
		return 'sys_users';
	}

	/**
	 * @return LCQuery
	 */
	public static function find(): LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array {
		return [
			[['username', 'login', 'password', 'salt', 'email', 'create_date'], 'required'],
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['daddy', 'deleted'], 'integer'],
			[['username', 'password', 'salt', 'email'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 64],
			[['login'], 'unique'],
			[['email'], 'unique']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array {
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
	public static function findByLogin(string $login): ?Users {
		return self::findOne(['login' => $login]);
	}

	public function applySalt() {
		$this->salt = sha1(uniqid((string)mt_rand(), true));
		$this->password = sha1($this->password.$this->salt);
	}

	/**
	 * @param $paramsArray
	 * @return bool
	 * @throws Throwable
	 */
	public static function createUser($paramsArray) {
		$newUser = new self();
		if ($newUser->loadArray($paramsArray)) {
			if (null === $newUser->salt) $newUser->applySalt();

			$newUser->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate(),
			]);
			return $newUser->save();
		}
		return false;
	}

}
