<?php
declare(strict_types = 1);

namespace app\models\user_rights;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\Magic;
use app\models\core\traits\ARExtended;
use app\models\relations\RelPrivilegesRights;
use app\models\user\CurrentUser;
use app\widgets\alert\AlertModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
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
 * @property UserRightInterface[] $userRights
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
			[['userRightsNames'], 'safe']
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
	 * Возвращает массив всех возможных прав
	 * @param string $path
	 * @return UserRightInterface[]
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetRightsList(string $path = self::RIGHTS_DIRECTORY, array $exludedRights = []):array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($path)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $model = Magic::GetUserRightModel($file->getRealPath())) {
				if (!in_array($model->formName(), $exludedRights)) $result[] = $model;
			}
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
		if ($this->isNewRecord) return;//Обработчик сохранения перевызовет метод после сохранения основной модели
		foreach ($userRightsNames as $className) {
			$relRight = new RelPrivilegesRights([
				'privilege' => $this->id,
				'right' => $className
			]);
			$relRight->save();
		}
	}

	/**
	 * @return UserRightInterface[]
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function getUserRights():array {
		$classNames = $this->userRightsNames;
		$result = [];
		foreach ($classNames as $className) {
			if (null !== $class = Magic::LoadClassByName($className, UserRightInterface::class)) $result[] = $class;
		}
		return $result;
	}

}