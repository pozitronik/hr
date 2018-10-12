<?php
declare(strict_types = 1);

namespace app\models\groups;

use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\groups\traits\Graph;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersGroups;
use app\models\user\CurrentUser;
use app\models\users\Users;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name Название
 * @property string $comment Описание
 * @property integer|null $daddy Пользователь, создавший группу
 * @property ActiveQuery|Users[] $relUsers Пользователи в группе
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Связь с релейшеном пользователей
 * @property ActiveQuery|Groups[] $relChildGroups Группы, дочерние по отношению к текущей
 * @property-write array $dropChildGroups Свойство для передачи массива отлинкуемых дочерних групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsParent Релейшен групп для получения дочерних групп
 * @property array $dropParentGroups Свойство для передачи массива отлинкуемых родительских групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsChild Релейшен групп для получения родительских групп
 * @property ActiveQuery|Groups[] $relParentGroups Группы, родительские по отношению к текущей
 * @property int $deleted
 *
 */
class Groups extends ActiveRecord {
	use ARExtended;
	use Graph;
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
			[['name'], 'string', 'max' => 512],
			[['relChildGroups', 'dropChildGroups', 'relParentGroups', 'dropParentGroups'], 'safe']
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
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
//		echo $x->createCommand()->rawSql;
//		die;
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function createGroup($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {

			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			if ($this->save()) {//При создании пересохраним, чтобы подтянуть прилинкованные свойства
				$this->loadArray($paramsArray);
				$this->save();
				return true;
			}
		}
		return false;
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function updateGroup($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			return $this->save();
		}
		return false;
	}

	/**
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsChild() {
		return $this->hasMany(RelGroupsGroups::class, ['parent_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery
	 */
	public function getRelChildGroups() {
		return $this->hasMany(self::class, ['id' => 'child_id'])->via('relGroupsGroupsChild');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param $childGroups
	 * @throws Throwable
	 */
	public function setRelChildGroups($childGroups):void {
		RelGroupsGroups::linkModels($this, $childGroups);
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropChildGroups
	 * @throws Throwable
	 */
	public function setDropChildGroups(array $dropChildGroups):void {
		RelGroupsGroups::unlinkModels($this, $dropChildGroups);
	}

	/**
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsParent() {
		return $this->hasMany(RelGroupsGroups::class, ['child_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery
	 */
	public function getRelParentGroups() {
		return $this->hasMany(self::class, ['id' => 'parent_id'])->via('relGroupsGroupsParent');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param $parentGroups
	 * @throws Throwable
	 */
	public function setRelParentGroups($parentGroups):void {
		RelGroupsGroups::linkModels($parentGroups, $this);
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropParentGroups
	 * @throws Throwable
	 */
	public function setDropParentGroups(array $dropParentGroups):void {
		RelGroupsGroups::unlinkModels($dropParentGroups, $this);
	}

}
