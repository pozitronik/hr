<?php
declare(strict_types = 1);

namespace app\models\groups;

use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\relations\RelUsersGroups;
use app\models\user\CurrentUser;
use app\models\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name Название
 * @property string $comment Описание
 * @property integer|null $daddy
 * @property ActiveQuery|Users[] $users
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups
 * @property int $deleted
 *
 */
class Groups extends ActiveRecord {
	use ARExtended;

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
			[['deleted', 'daddy'], 'integer'],
			[['create_date'], 'safe'],
			[['name'], 'string', 'max' => 512]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'comment' => 'Описание',
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'deleted' => 'Deleted'
		];
	}

	/**
	 * @return ActiveQuery|RelUsersGroups[]
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['group_id' => 'id']);
	}

	/**
	 * @return ActiveQuery|Users[]
	 */
	public function getUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
//		echo $x->createCommand()->rawSql;
//		die;
		/*switch ($this->id) {
			case 1:
				return [
					new Users([
						'id' => 2,
						'username' => 'Артемий Лебедев'
					]),
					new Users([
						'id' => 3,
						'username' => 'Юрий Дудь'
					]),
					new Users([
						'id' => 4,
						'username' => 'Гомер Симпсон'
					])
				];
			break;
			case 2:
				return [
					new Users([
						'id' => 5,
						'username' => 'Пабло Эскобар'
					]),
					new Users([
						'id' => 6,
						'username' => 'Свинка Пеппа'
					]),
					new Users([
						'id' => 7,
						'profile_image' => '7.gif',
						'username' => 'Малосольный Пончик'
					])
				];
			break;
			default:
				return [
					new Users([
						'id' => 8,
						'username' => 'Медведь Шатун'
					]),
					new Users([
						'id' => 9,
						'username' => 'Генадий Викторович'
					]),
					new Users([
						'id' => 10,
						'username' => 'Соседка по комнате'
					])
				];
			break;
		}*/

	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function createGroup($paramsArray) {
		if ($this->loadArray($paramsArray)) {

			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			return $this->save();
		}
		return false;
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function updateGroup($paramsArray) {
		if ($this->loadArray($paramsArray)) {
			return $this->save();
		}
		return false;
	}
}
