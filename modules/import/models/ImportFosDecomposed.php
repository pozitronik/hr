<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\groups\Groups;
use app\modules\import\models\fos\ImportFosChapter;
use app\modules\import\models\fos\ImportFosChapterCouch;
use app\modules\import\models\fos\ImportFosChapterLeader;
use app\modules\import\models\fos\ImportFosClusterProduct;
use app\modules\import\models\fos\ImportFosClusterProductLeader;
use app\modules\import\models\fos\ImportFosCommand;
use app\modules\import\models\fos\ImportFosCommandPosition;
use app\modules\import\models\fos\ImportFosDivisionLevel1;
use app\modules\import\models\fos\ImportFosDivisionLevel2;
use app\modules\import\models\fos\ImportFosDivisionLevel3;
use app\modules\import\models\fos\ImportFosDivisionLevel4;
use app\modules\import\models\fos\ImportFosDivisionLevel5;
use app\modules\import\models\fos\ImportFosFunctionalBlock;
use app\modules\import\models\fos\ImportFosFunctionalBlockTribe;
use app\modules\import\models\fos\ImportFosProductOwner;
use app\modules\import\models\fos\ImportFosTribe;
use app\modules\import\models\fos\ImportFosTribeLeader;
use app\modules\import\models\fos\ImportFosTribeLeaderIt;
use app\modules\import\models\fos\ImportFosUsers;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroupsRoles;
use app\models\users\Users;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_decomposed".
 *
 * @property int $id
 * @property string $num № п/п
 * @property int $position_id
 * @property int $user_id
 * @property int $functional_block
 * @property int $division_level_1
 * @property int $division_level_2
 * @property int $division_level_3
 * @property int $division_level_4
 * @property int $division_level_5
 * @property int $functional_block_tribe
 * @property int $tribe_id
 * @property int $cluster_product_id
 * @property int $command_id
 * @property int $command_position_id
 * @property int $chapter_id
 * @property int $domain Служебная метка очереди импорта
 */
class ImportFosDecomposed extends ActiveRecord {

	public const STEP_GROUPS = 0;
	public const STEP_USERS = 1;
	public const STEP_LINKING_USERS = 2;
	public const STEP_LINKING_GROUPS = 3;
	public const step_labels = [
		self::STEP_GROUPS => 'Импорт декомпозированных групп',
		self::STEP_USERS => 'Импорт декомпозированных пользователей',
		self::STEP_LINKING_USERS => 'Добавление пользователей в группы',
		self::STEP_LINKING_GROUPS => 'Построение иерархии групп'
	];

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
		return [
			[['position_id', 'user_id', 'functional_block', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'functional_block_tribe', 'tribe_id', 'cluster_product_id', 'command_id', 'command_position_id', 'chapter_id', 'domain'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'position_id' => 'Position ID',
			'user_id' => 'User ID',
			'functional_block' => 'Functional Block',
			'division_level_1' => 'Division Level 1',
			'division_level_2' => 'Division Level 2',
			'division_level_3' => 'Division Level 3',
			'division_level_4' => 'Division Level 4',
			'division_level_5' => 'Division Level 5',
			'functional_block_tribe' => 'Functional Block Tribe',
			'tribe_id' => 'Tribe ID',
			'cluster_product_id' => 'Cluster Product ID',
			'command_id' => 'Command ID',
			'command_position_id' => 'Command Position ID',
			'chapter_id' => 'Chapter ID',
			'domain' => 'Служебная метка очереди импорта'
		];
	}

	/**
	 * Разбираем декомпозированные данные и вносим в боевую таблицу
	 * @param null|int $domain
	 * @param int $step
	 * @return int Current step
	 * @throws Throwable
	 */
	public static function Import(?int $domain = null, int $step = self::STEP_GROUPS):int {
		//$result = [];//Сюда складируем сообщения
		/*Идём по таблицам декомпозиции, добавляя данные из них в соответствующие таблицы структуры*/

		switch ($step) {
			case self::STEP_GROUPS:/*Группы. Добавляем группу и её тип*/
				$data = ImportFosChapter::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosChapter $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Чаптер'));
				}
				$data = ImportFosClusterProduct::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosClusterProduct $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Кластер'));
				}
				$data = ImportFosCommand::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosCommand $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Команда'));
				}
				$data = ImportFosDivisionLevel1::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosDivisionLevel1 $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение первого уровня'));
				}
				$data = ImportFosDivisionLevel2::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosDivisionLevel2 $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение второго уровня'));
				}
				$data = ImportFosDivisionLevel3::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosDivisionLevel3 $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение третьего уровня'));
				}
				$data = ImportFosDivisionLevel4::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosDivisionLevel4 $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение четвёртого уровня'));
				}
				$data = ImportFosDivisionLevel5::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosDivisionLevel5 $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение пятого уровня'));
				}
				$data = ImportFosFunctionalBlock::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosFunctionalBlock $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Функциональный блок'));
				}
				$data = ImportFosFunctionalBlockTribe::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosFunctionalBlockTribe $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Функциональный блок трайба'));
				}
				$data = ImportFosTribe::find()->where(['domain' => $domain, 'hr_group_id' => null])->all();
				foreach ($data as $row) {
					/** @var ImportFosTribe $row */
					$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Трайб'));
				}
			break;
			case self::STEP_USERS:
				/*Пользователи, с должностями, емайлами и атрибутами*/
				/** @var ImportFosUsers[] $importFosUsers */
				$importFosUsers = ImportFosUsers::find()->where(['domain' => $domain, 'hr_user_id' => null])->all();
				foreach ($importFosUsers as $importFosUser) {

					$importFosUser->setAndSaveAttribute('hr_user_id', self::addUser($importFosUser->name, ArrayHelper::getValue($importFosUser->relPosition, 'name'), $importFosUser->email_alpha, [
						[
							'attribute' => 'Адрес',
							'type' => 'boolean',
							'field' => 'Удалённое рабочее место',
							"value" => $importFosUser->remote
						],
						[
							'attribute' => 'Адрес',
							'type' => 'string',
							'field' => 'Населённый пункт',
							"value" => ArrayHelper::getValue($importFosUser->relTown, 'name')
						],
						[
							'attribute' => 'Адрес',
							'type' => 'string',
							'field' => 'Внешний почтовый адрес',
							"value" => $importFosUser->email_sigma
						]
					]));
					/*Логика декомпозиции подразделений:
					Если функциональный блок = Розничный бизнес, то делаем группу из level2
					Если функциональный блок = Пуст, то делаем группу из level2
					В остальных случаях делаем группы level3 && level4 (если есть данные), level4 входит в level3,
					level5 игнорим
					*/
					if (in_array(ArrayHelper::getValue($importFosUser->relFunctionalBlock, 'name'), ['Розничный бизнес', null])) {
						if (null !== $id = ArrayHelper::getValue($importFosUser->relDivisionLevel2, 'hr_group_id')) {
							self::linkRole($id, $importFosUser->hr_user_id);
						}
					} else {
						$id3 = ArrayHelper::getValue($importFosUser->relDivisionLevel3, 'hr_group_id');
						$id4 = ArrayHelper::getValue($importFosUser->relDivisionLevel4, 'hr_group_id');

						self::linkRole($id4, $importFosUser->hr_user_id);
						self::linkRole($id3, $importFosUser->hr_user_id);
						RelGroupsGroups::linkModels($id3, $id4);
					}

					/*Позиции в командах всех пользователей через ImportFosCommandPosition */
					if (null !== $command = $importFosUser->relCommand) {//Пользователь может быть вне команды
						self::linkRole($command->hr_group_id, $importFosUser->hr_user_id, ArrayHelper::getValue(self::findUserCommandPosition($importFosUser->pkey, $command->pkey, $domain), 'name'));
					}
				}
			break;
			case self::STEP_LINKING_USERS:
				/** @var ImportFosChapterCouch[] $data */
				$data = ImportFosChapterCouch::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$chapters = ImportFosChapter::find()->where(['leader_id' => $row->id])->all();
					foreach ($chapters as $chapter) {
						/** @var ImportFosChapter $chapter */
						self::linkRole($chapter->hr_group_id, $row->relUsers->hr_user_id, 'Agile-коуч');
					}
				}
				/** @var ImportFosChapterLeader[] $data */
				$data = ImportFosChapterLeader::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$chapters = ImportFosChapter::find()->where(['leader_id' => $row->id])->all();
					foreach ($chapters as $chapter) {
						/** @var ImportFosChapter $chapter */
						self::linkRole($chapter->hr_group_id, $row->relUsers->hr_user_id, 'Лидер чаптера');
					}
				}
				/** @var ImportFosClusterProductLeader[] $data */
				$data = ImportFosClusterProductLeader::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$clusters = ImportFosClusterProduct::find()->where(['leader_id' => $row->id])->all();
					foreach ($clusters as $cluster) {
						/** @var ImportFosClusterProduct $cluster */
						self::linkRole($cluster->hr_group_id, $row->relUsers->hr_user_id, 'Лидер кластера');
					}
				}
				/** @var ImportFosProductOwner[] $data */
				$data = ImportFosProductOwner::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$commands = ImportFosCommand::find()->where(['owner_id' => $row->id])->all();
					foreach ($commands as $command) {
						/** @var ImportFosCommand $command */
						self::linkRole($command->hr_group_id, $row->relUsers->hr_user_id, 'Владелец продукта');
					}
				}
				/** @var ImportFosTribeLeader[] $data */
				$data = ImportFosTribeLeader::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$tribes = ImportFosTribe::find()->where(['leader_id' => $row->id])->all();
					foreach ($tribes as $tribe) {
						/** @var ImportFosTribe $tribe */
						self::linkRole($tribe->hr_group_id, $row->relUsers->hr_user_id, 'Лидер трайба');
					}
				}
				/** @var ImportFosTribeLeaderIt[] $data */
				$data = ImportFosTribeLeaderIt::find()->where(['domain' => $domain])->all();
				foreach ($data as $row) {
					$tribes = ImportFosTribe::find()->where(['leader_it_id' => $row->id])->all();
					foreach ($tribes as $tribe) {
						/** @var ImportFosTribe $tribe */
						self::linkRole($tribe->hr_group_id, $row->relUsers->hr_user_id, 'IT-Лидер трайба');
					}
				}
			break;
			case self::STEP_LINKING_GROUPS:
				/*Строим связи между группами
				1) (Бизнес-связи) Функциональный блок => трайб => кластер => команда
				2) (IT-связь) Трайб => Чаптер
				*/
				foreach (ImportFosFunctionalBlock::find()->where(['domain' => $domain])->all() as $fBlock) {
					foreach ($fBlock->relTribe as $tribe) {
						/** @var ImportFosFunctionalBlock $fBlock */
						RelGroupsGroups::linkModels($fBlock->hr_group_id, $tribe->hr_group_id);
					}

				}

				/** @var ImportFosTribe $tribe */
				foreach (ImportFosTribe::find()->where(['domain' => $domain])->all() as $tribe) {
					foreach ($tribe->relCluster as $cluster) {
						/** @var ImportFosTribe $tribe */
						RelGroupsGroups::linkModels($tribe->hr_group_id, $cluster->hr_group_id);
					}
					foreach ($tribe->relChapter as $chapter) {
						/** @var ImportFosChapter $tribe */
						RelGroupsGroups::linkModels($tribe->hr_group_id, $chapter->hr_group_id);//it-relation
					}
				}
				/** @var ImportFosClusterProduct $cluster */
				foreach (ImportFosClusterProduct::find()->where(['domain' => $domain])->all() as $cluster) {
					/** @var ImportFosClusterProduct $cluster */
					foreach ($cluster->relCommand as $command) {
						/** @var ImportFosClusterProduct $cluster */
						RelGroupsGroups::linkModels($cluster->hr_group_id, $command->hr_group_id);
					}
				}
			break;
		}
		return $step;

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
			$groupType = new RefGroupTypes([
				'name' => $type
			]);
			$groupType->save();
		}

		/** @var null|Groups $group */
		$group = Groups::find()->where(['name' => $name, 'type' => $groupType->id])->one();
		if ($group) return $group->id;

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
	 * @param string|null $position
	 * @param string|null $email
	 * @param array<array> $attributes
	 * @return int
	 * @throws Throwable
	 */
	public static function addUser(string $name, ?string $position, ?string $email, array $attributes = []):int {
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
		/** @noinspection IsEmptyFunctionUsageInspection */
		$user->createModel([
			'username' => $name,
			'login' => Utils::generateLogin(),
			'password' => Utils::gen_uuid(5),
			'salt' => null,
			'email' => empty($email)?Utils::generateLogin()."@localhost":$email,
			'deleted' => false
		]);
		$user->setAndSaveAttribute('position', $userPosition->id);

		if (null === $user->id) {
			Yii::debug($user, 'debug');
		}

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
			$attribute->createAttribute(['name' => $dynamic_attribute['attribute'], 'category' => 0]);
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
		if (null === $group = Groups::findModel($groupId)) return;
		$group = Groups::findModel($groupId);
		if (!in_array($groupId, ArrayHelper::getColumn($user->relGroups, 'id'))) {//Если пользователь не входит в группу, добавим его туда
			$user->relGroups = $group;
		}
		/** @noinspection IsEmptyFunctionUsageInspection */
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
	 * @param int $domain
	 * @return ImportFosCommandPosition|null
	 * @throws Throwable
	 */
	public static function findUserCommandPosition(int $userId, int $commandId, int $domain):?ImportFosCommandPosition {
		if (null !== $position = self::find()->where(['user_id' => $userId, 'command_id' => $commandId, 'domain' => $domain])->one()) {
			return ImportFosCommandPosition::findModel(['position_id'=>$position->command_position_id]);
		}
		return null;

	}
}
