<?php
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\Csv;
use app\helpers\Utils;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\users\Users;
use Throwable;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class MussRecord
 * Загрузка структуры из МУСС
 * 1. Загружаем список команд/групп. Нормализуем, сортируем. Привзяываем к типам.
 * 2. Загружаем список пользователей. Нормализуем, вставляем в соответствии с группами.
 * 3. Загружаем список должностей. Нормализуем, привязываем к пользователям.
 * 4. Загружаем список владельцев, нормализуем, привзяываем к пользователям и к командам.
 *
 *
 */
class MussRecord extends Model {

	private $models = [];

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 */
	public function addGroup(string $name, string $type):int {
		$group = Groups::find()->where(['name' => $name])->one();
		if ($group) return $group->id;
		$groupType = RefGroupTypes::find()->where(['name' => $type])->one();
		if (!$groupType) {
			$groupType = new RefGroupTypes([
				'name' => $type
			]);
			$groupType->save();
		}

		$group = new Groups();
		$group->createGroup([
			'name' => $name,
			'type' => $groupType->id
		]);
		return $group->id;
	}

	/**
	 * @param string $name
	 * @param string $position
	 * @return int
	 * @throws Throwable
	 */
	public function addUser(string $name, string $position):int {
		$user = Users::find()->where(['username' => $name])->one();
		if ($user) return $user->id;
		$userPosition = RefUserPositions::find()->where(['name' => $position])->one();
		if (!$userPosition) {
			$userPosition = new RefUserPositions([
				'name' => $position
			]);
			$userPosition->save();
		}

		$user = new Users();
		$user->createUser([
			'username' => $name,
			'login' => Utils::generateLogin($name),
			'password' => Utils::gen_uuid(5),
			'salt' => null,
			'email' => Utils::generateLogin($name)."@localhost"
		]);
		$user->setAndSaveAttribute('position', $userPosition->id);
		return $user->id;
	}

	/**
	 * @param string $userName
	 * @param string $groupName
	 */
	public function linkUsersGroups(string $userName, string $groupName):void {
		$user = Users::find()->where(['username' => $userName])->one();
		$group = Groups::find()->where(['name' => $groupName])->one();
		$user->relGroups = $group;
	}

	/**
	 * @param string $filename
	 * @throws Exception
	 */
	public function importRecords(string $filename):void {
		$array = Csv::csvToArray($filename);

		foreach ($array as $row) {
			$rowModel = new DynamicModel([
				'leader' => trim($row[0]),
				'chapter' => trim($row[1]),
				'group' => trim($row[2]),
				'groupType' => trim($row[3]),
				'position' => trim($row[4]),
				'username' => trim($row[5]),
				'owner' => trim($row[6])
			]);

			$this->models[] = $rowModel;
		}

		$transaction = Yii::$app->db->beginTransaction();

		try {
			foreach ($this->models as $model) {
				$this->addGroup($model->group, $model->groupType);
			}
			foreach ($this->models as $model) {
				$this->addUser($model->username, $model->position);
			}
			foreach ($this->models as $model) {
				$this->linkUsersGroups($model->username, $model->group);
			}

		} catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();
	}

}

