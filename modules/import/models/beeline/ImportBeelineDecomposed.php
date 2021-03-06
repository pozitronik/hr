<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline;

use app\components\pozitronik\helpers\ArrayHelper;
use app\components\pozitronik\helpers\Utils;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroupsRoles;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\import\models\beeline\active_record\ImportBeelineBoss;
use app\modules\import\models\beeline\active_record\ImportBeelineBranch;
use app\modules\import\models\beeline\active_record\ImportBeelineBusinessBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineCommand;
use app\modules\import\models\beeline\active_record\ImportBeelineDecomposed as ImportBeelineDecomposedAliasAR;
use app\modules\import\models\beeline\active_record\ImportBeelineDepartment;
use app\modules\import\models\beeline\active_record\ImportBeelineDirection;
use app\modules\import\models\beeline\active_record\ImportBeelineFunctionalBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineGroup;
use app\modules\import\models\beeline\active_record\ImportBeelineProductOwner;
use app\modules\import\models\beeline\active_record\ImportBeelineService;
use app\modules\import\models\beeline\active_record\ImportBeelineTribe;
use app\modules\import\models\beeline\active_record\ImportBeelineTribeLeader;
use app\modules\import\models\beeline\active_record\ImportBeelineUsers;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\relations\RelUserPositionsTypes;
use app\modules\users\models\Users;
use app\modules\users\models\UsersIdentifiers;
use Exception;
use Throwable;
use yii\web\NotFoundHttpException;

/**
 * Class ImportBeelineDecomposed
 */
class ImportBeelineDecomposed extends ImportBeelineDecomposedAliasAR {

	private const STEP_USERS_CHUNK_SIZE = 200;//Объём лимита выборки на шаге импорта пользователей

	public const STEP_GROUPS = 0;
	public const STEP_USERS = 1;
	public const STEP_LINKING_USERS = 2;
	public const STEP_LINKING_GROUPS = 3;
	public const LAST_STEP = self::STEP_LINKING_GROUPS + 1;
	public const step_labels = [
		self::STEP_GROUPS => 'Импорт декомпозированных групп',
		self::STEP_USERS => 'Импорт декомпозированных пользователей',
		self::STEP_LINKING_USERS => 'Добавление пользователей в группы',
		self::STEP_LINKING_GROUPS => 'Построение иерархии групп',
		self::LAST_STEP => 'Готово!'
	];

	/**
	 * Разбираем декомпозированные данные и вносим в боевую таблицу
	 * @param int $step
	 * @param array $errors -- прокидывание ошибок
	 * @return bool true - шаг выполнен, false - нужно повторить запрос (шаг разбит на подшаги)
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public static function Import(int $step = self::STEP_GROUPS, array &$errors = []):bool {
		set_time_limit(0);//для диминой кофемолки
		/*Идём по таблицам декомпозиции, добавляя данные из них в соответствующие таблицы структуры*/
		switch ($step) {
			case self::STEP_GROUPS:/*Группы. Добавляем группу и её тип*/
				return self::DoStepGroups();
			case self::STEP_USERS:
				return self::DoStepUsers($errors);
			case self::STEP_LINKING_USERS:
				return self::DoStepLinkingUsers();
			case self::STEP_LINKING_GROUPS:
				return self::DoStepLinkingGroups();
		}
		throw new NotFoundHttpException('Step not found');

	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 * @throws Exception
	 */
	public static function addGroup(string $name, string $type):int {
		if (empty($name)) return -1;

		$groupType = RefGroupTypes::find()->where(['name' => $type])->one();
		if (!$groupType) {
			$groupType = new RefGroupTypes(['name' => $type]);
			$groupType->save();
		}

		/** @var null|Groups $group */
		$group = Groups::find()->where(['name' => $name, 'type' => $groupType->id])->one();
		if ($group) return $group->id;

		$group = new Groups();
		$group->createModel(['name' => $name, 'type' => $groupType->id, 'deleted' => false]);
		return $group->id;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	private static function DoStepGroups():bool {
		foreach (ImportBeelineGroup::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Группа'));
		}
		foreach (ImportBeelineBranch::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Отдел'));
		}
		foreach (ImportBeelineService::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Служба'));
		}
		foreach (ImportBeelineDepartment::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Департамент'));
		}
		foreach (ImportBeelineDirection::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Дирекция'));
		}
		foreach (ImportBeelineFunctionalBlock::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Функциональный блок'));
		}
		foreach (ImportBeelineBusinessBlock::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Бизнес-блок'));
		}
		foreach (ImportBeelineTribe::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Трайб'));
		}
		foreach (ImportBeelineCommand::findAll(['hr_group_id' => null]) as $group) {
			$group->setAndSaveAttribute('hr_group_id', self::addGroup($group->name, 'Команда'));
		}

		return true;//Поскольку тут объём работы довольно мал, считаем, что он всегда успевает выполниться
	}

	/**
	 * @param array<string, string> $dynamic_attribute
	 * @param int $user_id
	 * @throws Throwable
	 * Добавляет атрибуту свойство
	 */
	public static function addAttributeProperty(array $dynamic_attribute, int $user_id):void {
		if (null === $attribute = DynamicAttributes::find()->where(['name' => $dynamic_attribute['attribute']])->one()) {
			$attribute = new DynamicAttributes();
			$attribute->createModel(['name' => $dynamic_attribute['attribute'], 'category' => 0]);
		}
		if (null === $field = $attribute->getPropertyByName($dynamic_attribute['field'])) {
			$field = new DynamicAttributeProperty(['attributeId' => $attribute->id, 'name' => $dynamic_attribute['field'], 'type' => $dynamic_attribute['type']]);
			$field->id = $attribute->setProperty($field, null);
		}
		RelUsersAttributes::linkModels($user_id, $attribute);
		$attribute->setUserProperty($user_id, $field->id, $dynamic_attribute['value']);
	}

	/**
	 * @param int $id
	 * @param string $name
	 * @param string|null $position
	 * @param int|null $positionType
	 * @param string|null $email
	 * @param array<array> $attributes
	 * @param array $errors -- ошибки импорта (сообщить админу, пусть сам разбирается)
	 * @return null|int
	 * @throws Throwable
	 */
	public static function updateUser(int $id, string $name, ?string $position, ?int $positionType, ?string $email, array $attributes = [], array &$errors = []):?int {
		if (null === $user = Users::findModel($id)) {
			$errors[] = [$name => ['id' => 'ID найден по внешнему идентификатору, но пользователь отсутствует в системной таблице']];
			return null;
		}

		$userPosition = RefUserPositions::find()->where(['name' => $position])->one();
		if (!$userPosition) {
			$userPosition = new RefUserPositions(['name' => $position]);
			$userPosition->save();
		}
		if (null !== $positionType) {//линкуем с типом должности. Должность уже создана во время декомпозиции, ее id записан в таблицу декомпозиции пользователя
			RelUserPositionsTypes::linkModels($user, $positionType);
		}

		$user->setAndSaveAttributes(['position' => $userPosition->id, 'email' => $email]);

		foreach ($attributes as $attribute) {
			self::addAttributeProperty($attribute, $user->id);
		}

		return $user->id;
	}

	/**
	 * Добавляет роль пользователя в группе (или возвращает существующую) по её имени
	 * @param string $roleName
	 * @param bool $leader
	 * @return int|null
	 */
	public static function addUserRole(string $roleName, bool $leader = false):?int {
		if (empty($roleName)) return null;
		if (null === $role = RefUserRoles::find()->where(['name' => $roleName])->one()) {
			$role = new RefUserRoles([
				'name' => $roleName,
				'boss_flag' => $leader
			]);
			$role->save();
		}
		return $role->id;
	}

	/**
	 * @param int $tn
	 * @param string $name
	 * @param string|null $position
	 * @param int|null $positionType
	 * @param string|null $email
	 * @param array<array> $attributes
	 * @param array $errors -- ошибки импорта (сообщить админу, пусть сам разбирается)
	 * @return null|int
	 * @throws Throwable
	 */
	public static function addUser(int $tn, string $name, ?string $position, ?int $positionType, ?string $email, array $attributes = [], array &$errors = []):?int {
		if (empty($name)) return -1;

		$userIdentifier = UsersIdentifiers::findModel(['tn' => $tn]);

		if (null !== $userIdentifier) {//нашли пользователя по табельнику, апдейтим его
			return self::updateUser($userIdentifier->user_id, $name, $position, $positionType, $email, $attributes, $errors);
		}

		$userPosition = RefUserPositions::find()->where(['name' => $position])->one();
		if (!$userPosition) {
			$userPosition = new RefUserPositions(['name' => $position]);
			$userPosition->save();
		}

		$user = new Users();
		$user->createModel(['username' => $name, 'login' => Utils::generateLogin(), 'password' => Utils::gen_uuid(5), 'salt' => null, 'email' => empty($email)?Utils::generateLogin()."@localhost":$email, 'deleted' => false]);
		$user->setAndSaveAttribute('position', $userPosition->id);
		(new UsersIdentifiers())->createModel(['user_id' => $user->id, 'tn' => $tn]);

		if (null !== $positionType) {//линкуем с типом должности. Должность уже создана во время декомпозиции, ее id записан в таблицу декомпозиции пользователя
			RelUserPositionsTypes::linkModels($user, $positionType);
		}

		if (null === $user->id) {
			$errors[] = [$name => $user->errors];
			return null;
		}

		foreach ($attributes as $attribute) {
			self::addAttributeProperty($attribute, $user->id);
		}
		return $user->id;
	}

	/**
	 * Добавляет пользователя в группу с линковкой роли
	 * @param null|int $groupId
	 * @param null|int $userId
	 * @param null|string $roleName
	 * @param bool $leader_role
	 * @throws Throwable
	 */
	public static function linkRole(?int $groupId, ?int $userId, ?string $roleName = null, bool $leader_role = false):void {
		if (null === $groupId || null === $userId) return;
		/** @var Users $user */
		if (null === $user = Users::findModel($userId)) return;
		/** @var null|Groups $group */
		if (null === Groups::findModel($groupId)) return;
		$group = Groups::findModel($groupId);
		if (!in_array($groupId, ArrayHelper::getColumn($user->relGroups, 'id'), true)) {//Если пользователь не входит в группу, добавим его туда
			$user->relGroups = $group;
		}
		if (!empty($roleName)) {
			RelUsersGroupsRoles::setRoleInGroup(self::addUserRole($roleName, $leader_role), $groupId, $userId);
		}

	}

	/**
	 * @param array $errors -- массив ошибок импорта
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepUsers(array &$errors = []):bool {
		if ([] === $importUsers = ImportBeelineUsers::find()->where(['hr_user_id' => null])->limit(self::STEP_USERS_CHUNK_SIZE)->all()) return true;

		foreach ($importUsers as $importUser) {
			/** @var ImportBeelineUsers $importUser */
			if (null === $userId = self::addUser((int)$importUser->user_tn, $importUser->name, $importUser->position, null, null, [
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Структурная принадлежность', "value" => $importUser->affiliation],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Тип', "value" => $importUser->user_type],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Номер профиля должности', "value" => $importUser->position_profile_number],
					['attribute' => 'Кадровые атрибуты', 'type' => 'boolean', 'field' => 'Руководитель', "value" => $importUser->is_boss],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Код компании', "value" => $importUser->company_code],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Локация', "value" => $importUser->location],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'ЦБО', "value" => $importUser->cbo],
				], $errors)
			) {//Импорт не получился, в $errors ошибки (имя пользователя => набор ошибок)
				$importUser->setAndSaveAttribute('hr_user_id', -1);//впишем ему отрицательный id, чтобы на следующей итерации пропустился
				continue; //пропустим засранца
			}

			$importUser->setAndSaveAttribute('hr_user_id', $userId);

			self::linkRole($importUser->relBeelineBusinessBlock?->hr_group_id, $importUser->hr_user_id, (2 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (2 === $importUser->level));//Бизнес-блок
			self::linkRole($importUser->relBeelineFunctionalBlock?->hr_group_id, $importUser->hr_user_id, (3 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (3 === $importUser->level));//Функциональный блок
			self::linkRole($importUser->relBeelineDirection?->hr_group_id, $importUser->hr_user_id, (4 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (4 === $importUser->level));//Дирекция
			self::linkRole($importUser->relBeelineDepartment?->hr_group_id, $importUser->hr_user_id, (5 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (5 === $importUser->level));//Департамент
			self::linkRole($importUser->relBeelineService?->hr_group_id, $importUser->hr_user_id, (6 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (6 === $importUser->level));//Служба
			self::linkRole($importUser->relBeelineBranch?->hr_group_id, $importUser->hr_user_id, (7 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (7 === $importUser->level));//Отдел
			self::linkRole($importUser->relBeelineGroup?->hr_group_id, $importUser->hr_user_id, (8 === $importUser->level)?$importUser->position:null, $importUser->is_boss && (8 === $importUser->level));//Группа

			self::linkRole($importUser->relBeelineTribe?->hr_group_id, $importUser->hr_user_id);//Трайб
			self::linkRole($importUser->relBeelineCommand?->hr_group_id, $importUser->hr_user_id);//Команда

		}
		return false;
	}

	/**
	 * тут раскупориваем руководителей
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepLinkingUsers():bool {
		foreach (ImportBeelineTribeLeader::find()->all() as $tribeLeader) {
			foreach (ImportBeelineTribe::findAll(['user_id' => $tribeLeader->user_id]) as $tribe) {
				self::linkRole($tribe->hr_group_id, $tribeLeader->relUsers?->hr_user_id, 'Лидер трайба');
			}
		}
		foreach (ImportBeelineProductOwner::find()->all() as $productOwner) {
			foreach (ImportBeelineCommand::findAll(['user_id' => $productOwner->user_id]) as $command) {
				self::linkRole($command->hr_group_id, $productOwner->relUsers?->hr_user_id, 'Владелец продукта');
			}
		}

		if ([] === $importUsers = ImportBeelineBoss::find()->where(['hr_user_id' => null])->all()) return true;

		foreach ($importUsers as $importUser) {
			/** @var ImportBeelineBoss $importUser */

			if (null === $user = Users::find()->where(['username' => $importUser->name])->one()) {/*Пользователя нет в БД*/
				$importUser->setAndSaveAttribute('hr_user_id', -1);//впишем ему отрицательный id, чтобы на следующей итерации пропустился
			} else {/*Пользователь есть в БД, ничего делать, наверное, не нужно */
				$importUser->setAndSaveAttribute('hr_user_id', $user->id);
			}
		}
		return false;
	}

	/**
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepLinkingGroups():bool {
		foreach (ImportBeelineBusinessBlock::find()->all() as $business_block) {
			foreach ($business_block->relFunctionalBlock as $functional_block) {
				RelGroupsGroups::linkModels($business_block->hr_group_id, $functional_block->hr_group_id);
			}
		}
		foreach (ImportBeelineFunctionalBlock::find()->all() as $functional_block) {
			foreach ($functional_block->relDirection as $direction) {
				RelGroupsGroups::linkModels($functional_block->hr_group_id, $direction->hr_group_id);
			}
		}
		foreach (ImportBeelineDirection::find()->all() as $direction) {
			foreach ($direction->relDepartment as $department) {
				RelGroupsGroups::linkModels($direction->hr_group_id, $department->hr_group_id);
			}
		}
		foreach (ImportBeelineDepartment::find()->all() as $department) {
			foreach ($department->relService as $service) {
				RelGroupsGroups::linkModels($department->hr_group_id, $service->hr_group_id);
			}
		}
		foreach (ImportBeelineService::find()->all() as $service) {
			foreach ($service->relBranch as $branch) {
				RelGroupsGroups::linkModels($service->hr_group_id, $branch->hr_group_id);
			}
		}
		foreach (ImportBeelineBranch::find()->all() as $branch) {
			foreach ($branch->relGroup as $group) {
				RelGroupsGroups::linkModels($branch->hr_group_id, $group->hr_group_id);
			}
		}
		return true;
	}

}