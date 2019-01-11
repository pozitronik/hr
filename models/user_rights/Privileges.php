<?php
declare(strict_types = 1);

namespace app\models\user_rights;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\Magic;
use app\models\core\traits\ARExtended;
use app\models\relations\RelPrivilegesRights;
use app\models\relations\RelUsersPrivileges;
use app\models\user\CurrentUser;
use app\models\users\Users;
use app\widgets\alert\AlertModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\UnknownClassException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class Privileges
 * Набор прав пользователя
 * @package app\models\user_rights\rights
 * @property int $id
 * @property string name
 * @property int $daddy
 * @property string $create_date
 * @property bool $deleted
 *
 * @property ActiveQuery|LCQuery|RelPrivilegesRights[] $relPrivilegesRights
 * @property string[] $userRightsNames
 * @property-write int[] $dropUserRights
 * @property ActiveQuery|RelUsersPrivileges[] $relUsersPrivileges
 * @property int $usersCount
 * @property ActiveQuery|Users $relUsers
 * @property-read UserRightInterface[] $userRights
 */
class Privileges extends ActiveRecord {
	use ARExtended;
	public const RIGHTS_DIRECTORY = '@app/models/user_rights/rights';

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_privileges';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id', 'daddy'], 'integer'],
			[['create_date'], 'safe'],
			[['deleted'], 'boolean'],
			[['name'], 'string', 'max' => 256],
			[['name'], 'required'],
			[['userRightsNames', 'dropUserRights'], 'safe']
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
			'userRights' => 'Права',
			'usersCount' => 'Пользователей'
		];
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 * @throws Exception
	 */
	public function createPrivilege(?array $paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate(),
				'deleted' => false
			]);
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					AlertModel::SuccessNotify();
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
	 */
	public function updatePrivilege(?array $paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}

	/**
	 * Возвращает массив всех возможных прав
	 * @param string $path
	 * @param UserRightInterface[] $excludedRights Массив моделей, исключённых из общего списка
	 * @return UserRightInterface[]
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetRightsList(string $path = self::RIGHTS_DIRECTORY, array $excludedRights = []):array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($path)), RecursiveIteratorIterator::SELF_FIRST);
		$excludedIds = ArrayHelper::getColumn($excludedRights, 'id');
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if (($file->isFile() && 'php' === $file->getExtension() && null !== $model = Magic::GetUserRightModel($file->getRealPath())) && (!in_array($model->id, $excludedIds))) $result[] = $model;
		}
		return $result;
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
	 */
	public function setUserRightsNames(array $userRightsNames):void {
		if ($this->isNewRecord || empty($userRightsNames)) return;//Обработчик сохранения перевызовет метод после сохранения основной модели
		foreach ($userRightsNames as $className) {
			$relRight = new RelPrivilegesRights([
				'privilege' => $this->id,
				'right' => $className
			]);
			$relRight->save();
		}
		$this->dropCaches();
	}

	/**
	 * Дропнет права в привилегии
	 * @param int[] $dropUserRights - ПОРЯДКОВЫЙ номер привилегии в списке (особенность работы CheckboxColumn), потому дополнительно делаем сопоставление номера к названию класса (который является тут айдишником)
	 * @throws Throwable
	 */
	public function setDropUserRights(array $dropUserRights):void {
		$dropUserRights = array_intersect_key($this->userRights, $dropUserRights);
		RelPrivilegesRights::unlinkModels($this, $dropUserRights);
		$this->dropCaches();
	}

	/**
	 * @return UserRightInterface[]
	 */
	public function getUserRights():array {
		return Yii::$app->cache->getOrSet(static::class."getUserRights".$this->id, function() {
			$result = [];
			$allRights = self::GetRightsList();
			foreach ($allRights as $right) {
				if (in_array($right->id, $this->userRightsNames)) $result[] = $right;
			}
			return $result;
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
		return Yii::$app->cache->getOrSet(static::class."DataOptions", function() {
			$items = self::GetRightsList();
			$result = [];
			foreach ($items as $key => $item) {
				$result[$item->id] = [
					'data-description' => $item->description
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

}