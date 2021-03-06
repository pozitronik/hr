<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\modules\users\models\relations\RelUserPositionsTypes;
use app\modules\users\models\UsersIdentifiers;
use app\components\pozitronik\helpers\ArrayHelper;
use app\components\pozitronik\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\groups\models\Groups;
use app\modules\import\models\fos\activerecord\ImportFosCommand;
use app\modules\import\models\fos\activerecord\ImportFosCommandPosition;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel1;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel2;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel3;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel4;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel5;
use app\modules\import\models\fos\activerecord\ImportFosFunctionalBlock;
use app\modules\import\models\fos\activerecord\ImportFosProductOwner;
use app\modules\import\models\fos\activerecord\ImportFosTribeLeader;
use app\modules\import\models\fos\activerecord\ImportFosTribeLeaderIt;
use app\modules\import\models\fos\activerecord\ImportFosUsers;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroupsRoles;
use app\modules\users\models\Users;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "import_fos_decomposed".
 *
 * @property int $id
 * @property string $num № п/п
 * @property int $position_id
 * @property int $user_id
 * @property int $functional_block_id
 * @property int $division_level_1_id
 * @property int $division_level_2_id
 * @property int $division_level_3_id
 * @property int $division_level_4_id
 * @property int $division_level_5_id
 * @property int $functional_block_tribe_id
 * @property int $tribe_id
 * @property int $cluster_product_id
 * @property int $command_id
 * @property int $command_position_id
 * @property int $chapter_id
 * @property int $domain Служебная метка очереди импорта
 */
class ImportFosDecomposed extends ActiveRecord {

	private const STEP_USERS_CHUNK_SIZE = 200;//Объём лимита выборки на шаге импорта пользователей

	public const STEP_GROUPS = 0;
	public const STEP_USERS = 1;
	public const STEP_LINKING_USERS = 2;
	public const STEP_LINKING_GROUPS = 3;
	public const LAST_STEP = self::STEP_LINKING_GROUPS + 1;
	public const step_labels = [self::STEP_GROUPS => 'Импорт декомпозированных групп', self::STEP_USERS => 'Импорт декомпозированных пользователей', self::STEP_LINKING_USERS => 'Добавление пользователей в группы', self::STEP_LINKING_GROUPS => 'Построение иерархии групп', self::LAST_STEP => 'Готово!'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_decomposed';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [[['position_id', 'user_id', 'functional_block_id', 'division_level_1_id', 'division_level_2_id', 'division_level_3_id', 'division_level_4_id', 'division_level_5_id', 'functional_block_tribe_id', 'tribe_id', 'cluster_product_id', 'command_id', 'command_position_id', 'chapter_id', 'domain'], 'integer']];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return ['id' => 'ID', 'position_id' => 'Position ID', 'user_id' => 'User ID', 'functional_block_id' => 'Functional Block', 'division_level_1_id' => 'Division Level 1', 'division_level_2_id' => 'Division Level 2', 'division_level_3_id' => 'Division Level 3', 'division_level_4_id' => 'Division Level 4', 'division_level_5_id' => 'Division Level 5', 'functional_block_tribe_id' => 'Functional Block Tribe', 'tribe_id' => 'Tribe ID', 'cluster_product_id' => 'Cluster Product ID', 'command_id' => 'Command ID', 'command_position_id' => 'Command Position ID', 'chapter_id' => 'Chapter ID', 'domain' => 'Служебная метка очереди импорта'];
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	private static function DoStepGroups():bool {
//		foreach (ImportFosChapter::findAll(['hr_group_id' => null]) as $chapter) {
//			$chapter->setAndSaveAttribute('hr_group_id', self::addGroup($chapter->name, 'Чаптер'));
//		}
//		foreach (ImportFosClusterProduct::findAll(['hr_group_id' => null]) as $cluster) {
//			$cluster->setAndSaveAttribute('hr_group_id', self::addGroup($cluster->name, 'Кластер'));
//		}
		foreach (ImportFosCommand::findAll(['hr_group_id' => null]) as $command) {
			$command->setAndSaveAttribute('hr_group_id', self::addGroup($command->name, 'Команда'));
		}

		foreach (ImportFosFunctionalBlock::findAll(['hr_group_id' => null]) as $functionalBlock) {
			$functionalBlock->setAndSaveAttribute('hr_group_id', self::addGroup($functionalBlock->name, 'Блок 1'));
		}
		foreach (ImportFosDivisionLevel1::findAll(['hr_group_id' => null]) as $division) {
			$division->setAndSaveAttribute('hr_group_id', self::addGroup($division->name, 'Блок 2'));
		}
		foreach (ImportFosDivisionLevel2::findAll(['hr_group_id' => null]) as $division) {
			$division->setAndSaveAttribute('hr_group_id', self::addGroup($division->name, 'Дирекция'));
		}
		foreach (ImportFosDivisionLevel3::findAll(['hr_group_id' => null]) as $division) {
			$division->setAndSaveAttribute('hr_group_id', self::addGroup($division->name, 'Департамент'));
		}
		foreach (ImportFosDivisionLevel4::findAll(['hr_group_id' => null]) as $division) {
			$division->setAndSaveAttribute('hr_group_id', self::addGroup($division->name, 'Служба'));
		}
		foreach (ImportFosDivisionLevel5::findAll(['hr_group_id' => null]) as $division) {
			$division->setAndSaveAttribute('hr_group_id', self::addGroup($division->name, 'Отдел'));
		}

//		foreach (ImportFosFunctionalBlockTribe::findAll(['hr_group_id' => null]) as $functionalBlockTribe) {
//			$functionalBlockTribe->setAndSaveAttribute('hr_group_id', self::addGroup($functionalBlockTribe->name, 'Функциональный блок трайба'));
//		}
//		foreach (ImportFosTribe::findAll(['hr_group_id' => null]) as $tribe) {
//			$tribe->setAndSaveAttribute('hr_group_id', self::addGroup($tribe->name, 'Трайб'));
//		}
		return true;//Поскольку тут объём работы довольно мал, считаем, что он всегда успевает выполниться
	}

	/**
	 * @param array $errors -- массив ошибок импорта
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepUsers(array &$errors = []):bool {
		/*Пользователи, с должностями, емайлами и атрибутами*/
		if ([] === $importFosUsers = ImportFosUsers::find()->where(['hr_user_id' => null])->limit(self::STEP_USERS_CHUNK_SIZE)->all()) return true;

		/** @var ImportFosUsers[] $importFosUsers */
		foreach ($importFosUsers as $importFosUser) {
			if (null === $userId = self::addUser($importFosUser->user_tn, $importFosUser->name, ArrayHelper::getValue($importFosUser->relPosition, 'name'), $importFosUser->position_type, $importFosUser->email_alpha, [
					['attribute' => 'Адрес', 'type' => 'boolean', 'field' => 'Удалённое рабочее место', "value" => $importFosUser->remote],
					['attribute' => 'Адрес', 'type' => 'string', 'field' => 'Населённый пункт', "value" => ArrayHelper::getValue($importFosUser->relTown, 'name')],
					['attribute' => 'Адрес', 'type' => 'string', 'field' => 'Внешний почтовый адрес', "value" => $importFosUser->email_sigma],
					['attribute' => 'Дата рождения', 'type' => 'string', 'field' => 'Дата рождения', "value" => $importFosUser->birthday],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Область экспертизы', "value" => $importFosUser->expert_area],
					['attribute' => 'Кадровые атрибуты', 'type' => 'string', 'field' => 'Совмещаемая роль', "value" => $importFosUser->combined_role]
				], $errors)
			) {//Импорт не получился, в $errors ошибки (имя пользователя => набор ошибок)
				$importFosUser->setAndSaveAttribute('hr_user_id', -1);//впишем ему отрицательный айдишник, чтобы на следующей итерации пропустился
				continue; //пропустим засранца
			}

			$importFosUser->setAndSaveAttribute('hr_user_id', $userId);

//			self::linkRole($importFosUser->relFunctionalBlock?->hr_group_id, $importFosUser->hr_user_id);//Блок 1
//			self::linkRole($importFosUser->relDivisionLevel1?->hr_group_id, $importFosUser->hr_user_id);//Блок 2
//			self::linkRole($importFosUser->relDivisionLevel2?->hr_group_id, $importFosUser->hr_user_id);//Дирекция
//			self::linkRole($importFosUser->relDivisionLevel3?->hr_group_id, $importFosUser->hr_user_id);//Департамент
//			self::linkRole($importFosUser->relDivisionLevel4?->hr_group_id, $importFosUser->hr_user_id);//Служба
//			self::linkRole($importFosUser->relDivisionLevel5?->hr_group_id, $importFosUser->hr_user_id);//Отдел

			/*Позиции в командах всех пользователей через ImportFosCommandPosition */
			if (null !== $command = $importFosUser->relCommand) {//Пользователь может быть вне команды
				self::linkRole($command->hr_group_id, $importFosUser->hr_user_id, ArrayHelper::getValue(self::findUserCommandPosition($importFosUser->id, $command->id), 'name'));
			}

		}
		return false;
	}

	/**
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepLinkingUsers():bool {

//		foreach (ImportFosChapterCouch::find()->all() as $couch) {//coach, хуйло неграмотное
//			/** @var ImportFosChapterCouch $couch */
//			$found = ImportFosChapter::findAll(['couch_id' => $couch->user_id]);
//			foreach ($found as $chapter) {
//				self::linkRole($chapter->hr_group_id, $couch->relUsers->hr_user_id, 'Agile-коуч');
//			}
//		}
//		foreach (ImportFosChapterLeader::find()->all() as $chapterLeader) {
//			/** @var ImportFosChapterLeader $chapterLeader */
//			$found = ImportFosChapter::findAll(['leader_id' => $chapterLeader->user_id]);
//			foreach ($found as $chapter) {
//				self::linkRole($chapter->hr_group_id, $chapterLeader->relUsers->hr_user_id, 'Лидер чаптера');
//			}
//		}
//		foreach (ImportFosClusterProductLeader::find()->all() as $clusterLeader) {
//			/** @var ImportFosClusterProductLeader $clusterLeader */
//			foreach (ImportFosClusterProduct::findAll(['leader_id' => $clusterLeader->user_id]) as $cluster) {
//				self::linkRole($cluster->hr_group_id, $clusterLeader->relUsers->hr_user_id, 'Лидер кластера');
//			}
//		}
//		foreach (ImportFosClusterProductLeaderIt::find()->all() as $clusterLeaderIt) {
//			/** @var ImportFosClusterProductLeaderIt $clusterLeaderIt */
//			foreach (ImportFosClusterProduct::findAll(['leader_it_id' => $clusterLeaderIt->user_id]) as $cluster) {
//				self::linkRole($cluster->hr_group_id, $clusterLeaderIt->relUsers->hr_user_id, 'IT-Лидер кластера');
//			}
//		}

		foreach (ImportFosProductOwner::find()->all() as $productOwner) {
			/** @var ImportFosProductOwner $productOwner */
			foreach (ImportFosCommand::findAll(['owner_id' => $productOwner->user_id]) as $command) {
				self::linkRole($command->hr_group_id, $productOwner->relUsers->hr_user_id, 'Владелец продукта');
			}
		}
		/*Лидер Блока 2*/
		foreach (ImportFosTribeLeader::find()->all() as $tribeLeader) {
			/** @var ImportFosTribeLeader $tribeLeader */
			foreach (ImportFosDivisionLevel1::find()->all() as $tribe) {
				self::linkRole($tribe->hr_group_id, $tribeLeader->relUsers->hr_user_id, 'Лидер Блока 2');
			}
		}
		/*Лидер Блока 1*/
		foreach (ImportFosTribeLeaderIt::find()->all() as $tribeLeaderIt) {
			/** @var ImportFosTribeLeaderIt $tribeLeaderIt */
			foreach (ImportFosFunctionalBlock::find()->all() as $tribe) {
				self::linkRole($tribe->hr_group_id, $tribeLeaderIt->relUsers->hr_user_id, 'Лидер Блока 1');
			}
		}

		return true;
	}

	/**
	 * Строим связи между группами
	 * 1) (Бизнес-связи) Функциональный блок => трайб => кластер => команда
	 * 2) (IT-связь) Трайб => Чаптер
	 * @return bool
	 * @throws Throwable
	 */
	private static function DoStepLinkingGroups():bool {
//		foreach (ImportFosDivisionLevel1::find()->all() as $level1) {
//			/** @var ImportFosDivisionLevel1 $level1 */
//		}
//
//
//		/** @var ImportFosFunctionalBlock $fBlock */
//		foreach (ImportFosFunctionalBlock::find()->all() as $fBlock) {
//			foreach ($fBlock->relTribe as $tribe) {
//				/** @var ImportFosFunctionalBlock $fBlock */
//				RelGroupsGroups::linkModels($fBlock->hr_group_id, $tribe->hr_group_id);
//			}
//
//		}
//
//		/** @var ImportFosTribe $tribe */
//		foreach (ImportFosTribe::find()->all() as $tribe) {
//			foreach ($tribe->relCluster as $cluster) {
//				/** @var ImportFosTribe $tribe */
//				RelGroupsGroups::linkModels($tribe->hr_group_id, $cluster->hr_group_id);
//			}
//			foreach ($tribe->relChapter as $chapter) {
//				/** @var ImportFosChapter $tribe */
//				RelGroupsGroups::linkModels($tribe->hr_group_id, $chapter->hr_group_id);//it-relation
//			}
//		}
//		/** @var ImportFosClusterProduct $cluster */
//		foreach (ImportFosClusterProduct::find()->all() as $cluster) {
//			/** @var ImportFosClusterProduct $cluster */
//			foreach ($cluster->relCommand as $command) {
//				/** @var ImportFosClusterProduct $cluster */
//				RelGroupsGroups::linkModels($cluster->hr_group_id, $command->hr_group_id);
//			}
//		}
		return true;
	}

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
	 * @param int $userId
	 * @param int $commandId
	 * @return ImportFosCommandPosition|null
	 * @throws Throwable
	 */
	public static function findUserCommandPosition(int $userId, int $commandId):?ImportFosCommandPosition {
		if (null !== $position = self::find()->where(['user_id' => $userId, 'command_id' => $commandId])->one()) {
			return ImportFosCommandPosition::findModel(['id' => $position->command_position_id]);
		}
		return null;

	}
}
