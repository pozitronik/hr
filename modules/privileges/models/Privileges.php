<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;

use app\models\core\traits\ARExtended;
use pozitronik\core\models\user_right\UserRightInterface;
use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;
use pozitronik\core\models\core_module\PluginsSupport;
use app\models\core\LCQuery;
use app\modules\privileges\models\relations\RelPrivilegesDynamicRights;
use app\modules\privileges\models\relations\RelPrivilegesRights;
use app\modules\privileges\models\relations\RelUsersPrivileges;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class Privileges
 * Набор прав пользователя
 * @package app\models\user_rights\rights
 * @property int $id
 * @property string name
 * @property int $daddy
 * @property string $create_date
 * @property bool $deleted
 * @property bool $default
 *
 * @property ActiveQuery|LCQuery|RelPrivilegesRights[] $relPrivilegesRights
 * @property string[] $userRightsNames
 * @property-write int[] $dropUserRights
 *
 * @property ActiveQuery|LCQuery|RelPrivilegesDynamicRights[] $relPrivilegesDynamicRights
 * @property int[] $userDynamicRightsIds
 * @property-write int[] $dropUserDynamicRights
 *
 * @property ActiveQuery|RelUsersPrivileges[] $relUsersPrivileges
 * @property int $usersCount
 * @property ActiveQuery|Users $relUsers
 * @property-read UserRightInterface[] $userRights Все права, как модельные, так и динамические
 *
 *
 */
class Privileges extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_privileges';
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
			[['id', 'daddy'], 'integer'],
			[['create_date'], 'safe'],
			[['deleted', 'default'], 'boolean'],
			[['deleted', 'default'], 'default', 'value' => false],
			[['name'], 'string', 'max' => 256],
			[['name'], 'required'],
			[['userRightsNames', 'dropUserRights', 'userDynamicRightsIds'], 'safe'],
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
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'deleted' => 'Deleted',
			'default' => 'Включена по умолчанию',
			'userRights' => 'Правила',
			'usersCount' => 'Пользователей'
		];
	}

	/**
	 * Связь с именами классов
	 * @return ActiveQuery|LCQuery|RelPrivilegesRights[]
	 */
	public function getRelPrivilegesRights() {
		return $this->hasMany(RelPrivilegesRights::class, ['privilege' => 'id']);
	}

	/**
	 * @return string[]
	 */
	public function getUserRightsNames():array {
		return ArrayHelper::getColumn($this->relPrivilegesRights, 'right');
	}

	/**
	 * @param string[] $userRightsNames
	 * @throws Throwable
	 */
	public function setUserRightsNames($userRightsNames):void {
		if ($this->isNewRecord || empty($userRightsNames)) return;//Обработчик сохранения перевызовет метод после сохранения основной модели
		RelPrivilegesRights::linkModels($this, $userRightsNames);
		$this->dropCaches();
	}

	/**
	 * @return int[]
	 */
	public function getUserDynamicRightsIds():array {
		return ArrayHelper::getColumn($this->relPrivilegesDynamicRights, 'right');
	}

	/**
	 * @param int[] $userRightsIds
	 * @throws Throwable
	 */
	public function setUserDynamicRightsIds($userRightsIds):void {
		if ($this->isNewRecord || empty($userRightsIds)) return;//Обработчик сохранения перевызовет метод после сохранения основной модели
		RelPrivilegesDynamicRights::linkModels($this, $userRightsIds);
		$this->dropCaches();
	}

	/**
	 * Дропнет права в привилегии
	 * @param int[] $dropUserRights - ПОРЯДКОВЫЙ номер привилегии в списке (особенность работы CheckboxColumn), потому дополнительно делаем сопоставление номера к названию класса (который является тут айдишником)
	 * @throws Throwable
	 */
	public function setDropUserRights(array $dropUserRights):void {
		$dropUserRightsClasses = array_intersect_key($this->userRights, array_flip($dropUserRights));
		foreach ($dropUserRightsClasses as $userRightsClass) {
			if (is_a($userRightsClass, DynamicUserRights::class)) {
				RelPrivilegesDynamicRights::unlinkModel($this, $userRightsClass->id);
			} else {
				RelPrivilegesRights::unlinkModel($this, $userRightsClass->id);
			}
		}

		$this->dropCaches();
	}

	/**
	 * @return UserRightInterface[]
	 */
	public function getUserRights():array {
		return Yii::$app->cache->getOrSet(static::class."getUserRights".$this->id, function() {
			$result = [];
			foreach (PluginsSupport::GetAllRights() as $right) {
				if (in_array($right->id, $this->userRightsNames)) $result[] = $right;
			}
			return array_merge($result, DynamicUserRights::find()->active()->where(['id' => $this->userDynamicRightsIds])->all());
		});
	}

	/**
	 * Удаляет все кеши, связанные с привилегией
	 */
	private function dropCaches():void {
		Yii::$app->cache->delete(static::class."DataOptions");
		Yii::$app->cache->delete(static::class."getUserRights".$this->id);
	}

	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array {
		return Yii::$app->cache->getOrSet(static::class."DataOptions", static function() {
			$result = [];
			foreach (PluginsSupport::GetAllRights() as $key => $item) {//только статика
				$result[$item->id] = [
					'data-description' => $item->description,
					'data-module' => $item->module
				];
			}
			/** @var DynamicUserRights $item */
			foreach (DynamicUserRights::find()->active()->all() as $key => $item) {//только динамика
				$result[$item->id] = [
					'data-description' => $item->description,
					'data-module' => $item->module
				];
			}
			return $result;
		});
	}

	/**
	 * @return RelUsersPrivileges[]|ActiveQuery
	 */
	public function getRelUsersPrivileges() {
		return $this->hasMany(RelUsersPrivileges::class, ['privilege_id' => 'id']);
	}

	/**
	 * @return Users|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersPrivileges');
	}

	/**
	 * @return int
	 */
	public function getUsersCount():int {
		return (int)$this->getRelUsers()->count();
	}

	/**
	 * Возвращает список привилегий с флагом "по умолчанию" => они обязательны для ВСЕХ пользователей
	 * @return self[]
	 */
	public static function GetDefaultPrivileges():array {
		return self::find()->where(['default' => true])->all();
	}

	/**
	 * @return LCQuery|RelPrivilegesDynamicRights[]|ActiveQuery
	 */
	public function getRelPrivilegesDynamicRights() {
		return $this->hasMany(RelPrivilegesDynamicRights::class, ['privilege' => 'id']);
	}

}