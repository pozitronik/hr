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
use app\modules\import\models\beeline\active_record\ImportBeelineBranch;
use app\modules\import\models\beeline\active_record\ImportBeelineBusinessBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineDepartment;
use app\modules\import\models\beeline\active_record\ImportBeelineDirection;
use app\modules\import\models\beeline\active_record\ImportBeelineFunctionalBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineGroup;
use app\modules\import\models\beeline\active_record\ImportBeelineService;
use app\modules\import\models\beeline\active_record\ImportBeelineUsers;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\relations\RelUserPositionsTypes;
use app\modules\users\models\Users;
use app\modules\users\models\UsersIdentifiers;
use Exception;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ImportBeelineDecomposed
 */
class ImportBeelineDecomposed extends active_record\ImportBeelineDecomposed {

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
			Yii::debug($user, 'debug');
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
	 * @return int|null
	 */
	public static function addUserRole(string $roleName):?int {
		if (empty($roleName)) return null;
		if (null === $role = RefUserRoles::find()->where(['name' => $roleName])->one()) {
			$role = new RefUserRoles(['name' => $roleName]);
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
	 * @throws Throwable
	 */
	public static function linkRole(?int $groupId, ?int $userId, ?string $roleName = null):void {
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
			RelUsersGroupsRoles::setRoleInGroup(self::addUserRole($roleName), $groupId, $userId);
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
			if (null === $userId = self::addUser($importUser->user_tn, $importUser->name, null, null, null, [
//					['attribute' => 'Адрес', 'type' => 'boolean', 'field' => 'Удалённое рабочее место', "value" => $importUser->remote],
//					['attribute' => 'Адрес', 'type' => 'string', 'field' => 'Населённый пункт', "value" => ArrayHelper::getValue($importUser->relTown, 'name')],
//					['attribute' => 'Адрес', 'type' => 'string', 'field' => 'Внешний почтовый адрес', "value" => $importUser->email_sigma],
//					['attribute' => 'Дата рождения', 'type' => 'string', 'field' => 'Дата рождения', "value" => $importUser->birthday],
//					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Область экспертизы', "value" => $importUser->expert_area],
//					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Совмещаемая роль', "value" => $importUser->combined_role]
				], $errors)
			) {//Импорт не получился, в $errors ошибки (имя пользователя => набор ошибок)
				$importUser->setAndSaveAttribute('hr_user_id', -1);//впишем ему отрицательный id, чтобы на следующей итерации пропустился
				continue; //пропустим засранца
			}

			$importUser->setAndSaveAttribute('hr_user_id', $userId);

			self::linkRole($importUser->relBeelineBusinessBlock->hr_group_id, $importUser->hr_user_id);//Бизнес-блок
			self::linkRole($importUser->relBeelineFunctionalBlock?->hr_group_id, $importUser->hr_user_id);//Функциональный блок
			self::linkRole($importUser->relBeelineDirection?->hr_group_id, $importUser->hr_user_id);//Дирекция
			self::linkRole($importUser->relBeelineDepartment?->hr_group_id, $importUser->hr_user_id);//Департамент
			self::linkRole($importUser->relBeelineService?->hr_group_id, $importUser->hr_user_id);//Служба
			self::linkRole($importUser->relBeelineBranch?->hr_group_id, $importUser->hr_user_id);//Отдел
			self::linkRole($importUser->relBeelineGroup?->hr_group_id, $importUser->hr_user_id);//Отдел

		}
		return false;
	}

	/**
	 * @return bool
	 */
	private static function DoStepLinkingUsers():bool {
		return true;
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