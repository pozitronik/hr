<?php /** @noinspection BadExceptionsProcessingInspection */
/** @noinspection ForeachSourceInspection */
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\ArrayHelper;
use app\helpers\Csv;
use app\helpers\Utils;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersGroupsRoles;
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

	public function clearDB() {

		$sql = "TRUNCATE sys_exceptions; TRUNCATE sys_groups; TRUNCATE sys_users; TRUNCATE sys_competencies_boolean; TRUNCATE sys_competencies_date; TRUNCATE sys_competencies_integer; TRUNCATE sys_competencies_percent; TRUNCATE sys_users_options;
				TRUNCATE sys_competencies_string; TRUNCATE sys_competencies_text; TRUNCATE sys_competencies_time; TRUNCATE rel_users_groups_roles; TRUNCATE rel_users_groups; TRUNCATE rel_users_competencies; TRUNCATE rel_groups_groups;
				INSERT INTO hr.sys_users (id, username, login, password, salt, email, comment, create_date, profile_image, daddy, deleted, position) VALUES (1, 'admin', 'admin', '820f86dc3ddc97f1ffc3b27e5a04a2a177a833a6', '9d0c7ed0b2d182885c168924bdd8e11ffbc2cad5', 'admin@POZITRONEBOOK', 'Системный администратор', '2018-10-29 15:00:27', '1.jpg', null, 0, null);";
		Yii::$app->db->createCommand($sql)->execute();

	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 * @throws Exception
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
			'login' => Utils::generateLogin(),
			'password' => Utils::gen_uuid(5),
			'salt' => null,
			'email' => Utils::generateLogin()."@localhost"
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
	 * @param string $groupName
	 * @param string $userName
	 * @param int $role
	 */
	public function linkRole(string $groupName, string $userName, int $role = Groups::OWNER):void {
		if ('' === $userName || '' === $groupName) return;
		$user = Users::find()->where(['username' => $userName])->one();//Предполагаем, что пользователь добавлен в бд
		if (!$user) return;
		$group = Groups::find()->where(['name' => $groupName])->one();
		if (!in_array($group->id, ArrayHelper::getColumn($user->relGroups, 'id'))) {//Если пользователь не входит в группу, добавим его туда
			$user->relGroups = $group;
		}
		RelUsersGroupsRoles::setRoleInGroup($role, $group->id, $user->id);
	}

	/**
	 * @param array $chapters
	 * @throws Throwable
	 */
	public function addChapters(array $chapters):void {
		/*1. Добавляем чаптер. 2. Добавляем лидера. 3. Всем группам добавляем чаптер, как родителя*/
		foreach ($chapters as $chapter => $data) {
			$chapterId = $this->addGroup($chapter, 'Chapter');
			$this->linkRole($chapter, $data['leader'], Groups::LEADER);
			foreach ($data['groups'] as $groupName) {
				$group = Groups::find()->where(['name' => $groupName])->one();
				RelGroupsGroups::linkModels($chapterId, $group);
			}
		}

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
			$owners = [];
			foreach ($this->models as $model) {
				$owners[$model->group][] = $model->owner;
			}
			$owners = ArrayHelper::array_unique($owners);
			foreach ($owners as $group => $owner) {
				$this->linkRole($group, $owner[0]);
			}
			$chapters = [];
			foreach ($this->models as $model) {
				$chapters[$model->chapter]['leader'] = $model->leader;
				if (!in_array($model->group, ['', 'ЧАПТЕР', '#Н/Д'])) $chapters[$model->chapter]['groups'][] = $model->group;
			}

			foreach ($chapters as &$chapter) {
				if (!ArrayHelper::getValue($chapter, 'groups')) $chapter['groups'] = [];
				$chapter['groups'] = ArrayHelper::array_unique($chapter['groups']);
			}

			unset($chapter);

			$this->addChapters($chapters);

		} catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();
	}

	/**
	 * Быстрофункция для линковки всех чаптеров в трайб
	 */
	public function linkChapters():void {
		$tribe = Groups::find()->where(['type' => Groups::TRIBE])->one();//Взяли трайб
		$chapters = Groups::find()->where(['type' => Groups::CHAPTER])->all();
		$transaction = Yii::$app->db->beginTransaction();

		try {
			RelGroupsGroups::linkModels($tribe, $chapters);
		} catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();
	}

}

