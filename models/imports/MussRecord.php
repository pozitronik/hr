<?php
declare(strict_types = 1);

use app\helpers\Csv;
use app\helpers\Utils;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\users\Users;
use yii\base\DynamicModel;
use yii\base\Model;

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

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 */
	public function addGroup(string $name, string $type):int {
		$name = trim($name);
		$type = trim($type);
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
	 */
	public function addUser(string $name, string $position):int {
		$name = trim($name);
		$position = trim($position);
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
		$user->position = $userPosition->id;
		return $user->id;
	}

	/**
	 * @param string $filename
	 */
	public function importRecords(string $filename):void {
		$array = Csv::csvToArray($filename);

		$rowModel = new DynamicModel(['leader', 'chapter', 'group', 'groupType', 'position', 'username', 'owner']);
		foreach ($array as $row) {
			if ($rowModel->load($row)) {
				//todo
			}
		}


	}

}

?>