<?php /** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\ArrayHelper;
use app\helpers\Csv;
use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroupsRoles;
use app\models\users\Users;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class
 * Импорт из ФОС (хз, wtf)
 * @package app\models\imports
 *
 * 1. Проходимся по всем колонкам, которые имеют отношение к группам, нормализуем, сортируем, привязываем к типам.
 * 2. Учитываем связи групп.
 * 3. Проходимся по пользователям, аналогично. Связываем с группами.
 * 4. Проходимся по должностям и ролям в группах, распихиваем по пользователям и по группам.
 * 5. Проходимся по аттрибутам, добавляем в компетенции (которые и есть произвольные атрибуты).
 * 6. Иерархия: Трайб->Чаптеры->Команды. Кластеры/Продукты делаем атрибутами.
 */
class FosRecord extends Model {
	public const OWNER = 4;
	public const LEADER = 1;
	public const LEADER_IT = 2;
//	public const TRIBE = 6;
//	public const CHAPTER = 5;


	public $id;//не используем
	public $position_id;//не используем
	public $position;//должность
	public $username;//пользователь
	public $block;//группа
	public $division_level1;//атрибут
	public $division_level2;//атрибут
	public $division_level3;//Не используем
	public $division_level4;//Не используем
	public $division_level5;//Не используем
	public $urm;//атрибут компетенции
	public $city;//атрибут компетенции
	public $tribe_block;//группа
	public $tribe_id;//-
	public $tribe_code;//-
	public $tribe;//группа
	public $tribe_leader_th;//-
	public $tribe_leader_username;//роль
	public $tribe_leader_it_th;//-
	public $tribe_leader_it_username;//роль
	public $cluster_id;//-
	public $cluster_code;//-
	public $cluster;//группа
	public $cluster_leader_th;//-
	public $cluster_leader;//роль
	public $command_id;//-
	public $command_code;//-
	public $command;//группа
	public $command_type;//тип группы
	public $product_owner;//роль
	public $position_in_command_id;//-
	public $position_in_command_code;//-
	public $position_in_command;//роль
	public $chapter_id;//-
	public $chapter_code;//-
	public $chapter;//группа
	public $chapter_leader_th;//-
	public $chapter_leader;//роль
	public $email_sigma;//атрибут компетенции
	public $email;//атрибут базовый

	/**
	 * @param string $filename
	 * @throws Exception
	 * @throws Throwable
	 */
	public function importRecords(string $filename):void {
		$array = Csv::csvToArray($filename);
		$models = [];

		foreach ($array as $row) {
			$rowModel = new self([
				'id' => trim($row[0]),
				'position_id' => trim($row[1]),
				'position' => trim($row[2]),
				'username' => trim($row[3]),
				'block' => trim($row[4]),
				'division_level1' => trim($row[5]),
				'division_level2' => trim($row[6]),
				'division_level3' => trim($row[7]),
				'division_level4' => trim($row[8]),
				'division_level5' => trim($row[9]),
				'urm' => trim($row[10]),
				'city' => trim($row[11]),
				'tribe_block' => trim($row[12]),
				'tribe_id' => trim($row[13]),
				'tribe_code' => trim($row[14]),
				'tribe' => trim($row[15]),
				'tribe_leader_th' => trim($row[16]),
				'tribe_leader_username' => trim($row[17]),
				'tribe_leader_it_th' => trim($row[18]),
				'tribe_leader_it_username' => trim($row[19]),
				'cluster_id' => trim($row[20]),
				'cluster_code' => trim($row[21]),
				'cluster' => trim($row[22]),
				'cluster_leader_th' => trim($row[23]),
				'cluster_leader' => trim($row[24]),
				'command_id' => trim($row[25]),
				'command_code' => trim($row[26]),
				'command' => trim($row[27]),
				'command_type' => trim($row[28]),
				'product_owner' => trim($row[29]),
				'position_in_command_id' => trim($row[30]),
				'position_in_command_code' => trim($row[31]),
				'position_in_command' => trim($row[32]),
				'chapter_id' => trim($row[33]),
				'chapter_code' => trim($row[34]),
				'chapter' => trim($row[35]),
				'chapter_leader_th' => trim($row[36]),
				'chapter_leader' => trim($row[37]),
				'email_sigma' => trim($row[38]),
				'email' => trim($row[39])
			]);

			$models[] = $rowModel;
		}

		$transaction = Yii::$app->db->beginTransaction();

		try {
			/*Всё, что группы*/
			/** @var self $model */
			foreach ($models as $model) {
				$this->addGroup($model->block, 'Функциональный блок');
				$this->addGroup($model->tribe, 'Трайб');
				//$this->addGroup($model->cluster, 'Кластер');
				$this->addGroup($model->command, $model->command_type);
				$this->addGroup($model->chapter, 'Чаптер');
			}
			/*Всё, что просто юзеры*/
			foreach ($models as $model) {
				$attributes = [
					[
						'attribute' => 'Адрес',
						'type' => 'boolean',
						'field' => 'Удалённое рабочее место',
						"value" => !empty($model->urm)
					],
					[
						'attribute' => 'Адрес',
						'type' => 'string',
						'field' => 'Населённый пункт',
						"value" => $model->city
					],
					[
						'attribute' => 'Адрес',
						'type' => 'string',
						'field' => 'Внешний почтовый адрес',
						"value" => $model->email_sigma
					],
					[
						'attribute' => 'Кластер/продукт',
						'type' => 'string',
						'field' => 'Название',
						"value" => $model->cluster
					],
					[
						'attribute' => 'Кластер/продукт',
						'type' => 'string',
						'field' => 'Лидер',
						"value" => $model->cluster_leader
					]

				];
				$this->addUser($model->username, $model->position, $model->email, $attributes);
			}

			/*Всё, что юзеры других колонок*/
			foreach ($models as $model) {
				$this->addUser($model->tribe_leader_username, 'Управляющий директор', '');
			}

			/*Всё, что роли*/
			foreach ($models as $model) {

				$this->linkRole($model->tribe, $model->tribe_leader_username, self::LEADER);
				$this->linkRole($model->tribe, $model->tribe_leader_it_username, self::LEADER_IT);
//				$this->linkRole($model->cluster, $model->cluster_leader, self::LEADER);
				$this->linkRole($model->command, $model->product_owner);
				$this->linkRole($model->chapter, $model->chapter_leader, self::LEADER);

				if (null !== $role_id = $this->addUserRole($model->position_in_command)) {
					$this->linkRole($model->command, $model->username, $role_id);
				}
			}

		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $t) {
			$transaction->rollBack();
			return;
		}

		$transaction->commit();

		foreach ($models as $model) {
			if (!empty($model->tribe) && !empty($model->chapter) && (null !== $tribe = Groups::find()->where(['name' => $model->tribe])->one()) && (null !== $chapter = Groups::find()->where(['name' => $model->chapter])->one())) {
				RelGroupsGroups::linkModels($tribe, $chapter);
			}
		}
		foreach ($models as $model) {
			if (!empty($model->command) && !empty($model->chapter) && (null !== $command = Groups::find()->where(['name' => $model->command])->one()) && (null !== $chapter = Groups::find()->where(['name' => $model->chapter])->one())) {
				RelGroupsGroups::linkModels($chapter, $command);
			}
		}
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 * @throws Exception
	 */
	public function addGroup(string $name, string $type):int {
		if (empty($name)) return -1;
		/** @var null|Groups $group */
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
			'type' => $groupType->id,
			'deleted' => false
		]);
		return $group->id;
	}

	/**
	 * @param string $name
	 * @param string $position
	 * @param string $email
	 * @param array<array> $attributes
	 * @return int
	 * @throws Throwable
	 */
	public function addUser(string $name, string $position, string $email, array $attributes = []):int {
		if (empty($name)) return -1;
		/** @var null|Users $user */
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
			'email' => ('' === $email)?Utils::generateLogin()."@localhost":$email,
			'deleted' => false
		]);
		$user->setAndSaveAttribute('position', $userPosition->id);

		foreach ($attributes as $attribute) {
			$this->addAttributeProperty($attribute, $user->id);
		}
		return $user->id;
	}

	/**
	 * @param array<string, string> $dynamic_attribute
	 * @param int $user_id
	 * @throws Throwable
	 * Добавляет атрибуту свойство
	 */
	public function addAttributeProperty(array $dynamic_attribute, int $user_id):void {
		if (null === $attribute = DynamicAttributes::find()->where(['name' => $dynamic_attribute['attribute']])->one()) {
			$attribute = new DynamicAttributes();
			$attribute->createCompetency(['name' => $dynamic_attribute['attribute'], 'category' => 0]);
		}
		if (null === $field = $attribute->getPropertyByName($dynamic_attribute['field'])) {
			$field = new DynamicAttributeProperty([
				'attributeId' => $attribute->id,
				'name' => $dynamic_attribute['field'],
				'type' => $dynamic_attribute['type']
			]);
			$field->id = $attribute->setProperty($field, null);
		}
		RelUsersAttributes::linkModels($user_id, $attribute);
		$attribute->setUserProperty($user_id, $field->id, $dynamic_attribute['value']);
	}

	/**
	 * @param string $groupName
	 * @param string $userName
	 * @param int $role
	 */
	public function linkRole(string $groupName, string $userName, int $role = self::OWNER):void {
		if ('' === $userName || '' === $groupName) return;
		/** @var null|Users $user */
		$user = Users::find()->where(['username' => $userName])->one();//Предполагаем, что пользователь добавлен в бд
		if (!$user) return;
		/** @var null|Groups $group */
		$group = Groups::find()->where(['name' => $groupName])->one();
		if (!in_array($group->id, ArrayHelper::getColumn($user->relGroups, 'id'))) {//Если пользователь не входит в группу, добавим его туда
			$user->relGroups = $group;
		}
		RelUsersGroupsRoles::setRoleInGroup($role, $group->id, $user->id);
	}

	/**
	 * @param string $roleName
	 * @return int|null
	 */
	public function addUserRole(string $roleName):?int {
		if (empty($roleName)) return null;
		if (null === $role = RefUserRoles::find()->where(['name' => $roleName])->one()) {
			$role = new RefUserRoles(['name' => $roleName]);
			$role->save();
		}
		return $role->id;
	}

}