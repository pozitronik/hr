<?php
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\groups\Groups;
use app\models\imports\fos\ImportFosChapter;
use app\models\imports\fos\ImportFosChapterCouch;
use app\models\imports\fos\ImportFosChapterLeader;
use app\models\imports\fos\ImportFosClusterProduct;
use app\models\imports\fos\ImportFosClusterProductLeader;
use app\models\imports\fos\ImportFosCommand;
use app\models\imports\fos\ImportFosDivisionLevel1;
use app\models\imports\fos\ImportFosDivisionLevel2;
use app\models\imports\fos\ImportFosDivisionLevel3;
use app\models\imports\fos\ImportFosDivisionLevel4;
use app\models\imports\fos\ImportFosDivisionLevel5;
use app\models\imports\fos\ImportFosFunctionalBlock;
use app\models\imports\fos\ImportFosFunctionalBlockTribe;
use app\models\imports\fos\ImportFosProductOwner;
use app\models\imports\fos\ImportFosTribe;
use app\models\imports\fos\ImportFosTribeLeader;
use app\models\imports\fos\ImportFosTribeLeaderIt;
use app\models\imports\fos\ImportFosUsers;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersGroupsRoles;
use app\models\users\Users;
use Exception;
use Throwable;
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
 * @property int $domain Служеная метка очереди импорта
 */
class ImportFosDecomposed extends ActiveRecord {
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
	 */
	public static function Import(?int $domain = null) {
		$result = [];//Сюда складируем сообщения
		/*Идём по таблицам декомпозиции, добавляя данные из них в соответствующие таблицы структуры*/
		/*Группы. Добавляем группу и её тип*/
		$data = ImportFosChapter::find()->where(['domain' => $domain])->all();/*todo: only NOT IMPORTED GROUPS*/
		foreach ($data as $row) {
			/** @var ImportFosChapter $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Чаптер'));
		}
		$data = ImportFosClusterProduct::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosClusterProduct $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Кластер'));
		}
		$data = ImportFosCommand::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosCommand $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Команда'));
		}
		$data = ImportFosDivisionLevel1::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel1 $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение первого уровня'));
		}
		$data = ImportFosDivisionLevel2::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel2 $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение второго уровня'));
		}
		$data = ImportFosDivisionLevel3::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel3 $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение третьего уровня'));
		}
		$data = ImportFosDivisionLevel4::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel4 $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение четвёртого уровня'));
		}
		$data = ImportFosDivisionLevel5::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel5 $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Подразделение пятого уровня'));
		}
		$data = ImportFosFunctionalBlock::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosFunctionalBlock $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Функциональный блок'));
		}
		$data = ImportFosFunctionalBlockTribe::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosFunctionalBlockTribe $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Функциональный блок трайба'));
		}
		$data = ImportFosTribe::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosTribe $row */
			$row->setAndSaveAttribute('hr_group_id', self::addGroup($row->name, 'Трайб'));
		}
		/*Пользователи, с должностями, емайлами и атрибутами*/
		$data = ImportFosUsers::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {

			/** @var ImportFosUsers $row */
			$row->setAndSaveAttribute('hr_user_id', self::addUser($row->name, ArrayHelper::getValue($row->relPosition, 'name'), $row->email_alpha, [
				[
					'attribute' => 'Адрес',
					'type' => 'boolean',
					'field' => 'Удалённое рабочее место',
					"value" => $row->remote
				],
				[
					'attribute' => 'Адрес',
					'type' => 'string',
					'field' => 'Населённый пункт',
					"value" => ArrayHelper::getValue($row->relTown, 'name')
				],
				[
					'attribute' => 'Адрес',
					'type' => 'string',
					'field' => 'Внешний почтовый адрес',
					"value" => $row->email_sigma
				]
			]));
			//В функциональный блок не включаем, это только для групп

			/*Включение в подразделения без ролей*/
			//Пока игнорим: непонятно, нужно или нет, а если нужно - то как. Можно добавлять пользователя в каждую группу, а можно добавить в нижний уровень, связав группы по иерархии Спросить у Рога
//			self::linkRole(ArrayHelper::getValue($row->division_level1, 'id'), $row->hr_user);
//			self::linkRole(ArrayHelper::getValue($row->division_level2, 'id'), $row->hr_user);
//			self::linkRole(ArrayHelper::getValue($row->division_level3, 'id'), $row->hr_user);
//			self::linkRole(ArrayHelper::getValue($row->division_level4, 'id'), $row->hr_user);
//			self::linkRole(ArrayHelper::getValue($row->division_level5, 'id'), $row->hr_user);

			/*Позиции в командах всех пользователей через ImportFosCommandPosition */

		}
		/** @var ImportFosChapterCouch[] $data */
		$data = ImportFosChapterCouch::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$chapters = ImportFosChapter::find()->where(['leader_id' => $row->user_id])->all();
			foreach ($chapters as $chapter) {
				/** @var ImportFosChapter $chapter */
				self::linkRole($chapter->hr_group_id, $row->hr_user_id, 'Agile-коуч');
			}
		}
		/** @var ImportFosChapterLeader[] $data */
		$data = ImportFosChapterLeader::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$chapters = ImportFosChapter::find()->where(['leader_id' => $row->user_id])->all();
			foreach ($chapters as $chapter) {
				/** @var ImportFosChapter $chapter */
				self::linkRole($chapter->hr_group_id, $row->hr_user_id, 'Лидер чаптера');
			}
		}
		/** @var ImportFosClusterProductLeader[] $data */
		$data = ImportFosClusterProductLeader::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$clusters = ImportFosClusterProduct::find()->where(['leader_id' => $row->user_id])->all();
			foreach ($clusters as $cluster) {
				/** @var ImportFosClusterProduct $cluster */
				self::linkRole($cluster->hr_group_id, $row->hr_user_id, 'Лидер кластера');
			}
		}
		/** @var ImportFosProductOwner[] $data */
		$data = ImportFosProductOwner::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$commands = ImportFosCommand::find()->where(['owner_id' => $row->user_id])->all();
			foreach ($commands as $command) {
				/** @var ImportFosCommand $command */
				self::linkRole($command->hr_group_id, $row->hr_user_id, 'Владелец продукта');
			}
		}
		$data = ImportFosTribeLeader::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$tribes = ImportFosTribe::find()->where(['leader_id' => $row->user_id])->all();
			foreach ($tribes as $tribe) {
				/** @var ImportFosTribe $tribe */
				self::linkRole($tribe->hr_group_id, $row->hr_user_id, 'Лидер трайба');
			}
		}
		$data = ImportFosTribeLeaderIt::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			$tribes = ImportFosTribe::find()->where(['leader_it_id' => $row->user_id])->all();
			foreach ($tribes as $tribe) {
				/** @var ImportFosTribe $tribe */
				self::linkRole($tribe->hr_group_id, $row->hr_user_id, 'IT-Лидер трайба');
			}
		}

	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return int
	 * @throws Exception
	 */
	public static function addGroup(string $name, string $type):int {
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
			\Yii::debug($user, 'debug');
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
	 * @param int $groupId
	 * @param int $userId
	 * @param ?string $roleName
	 */
	public static function linkRole(int $groupId, int $userId, ?string $roleName = null):void {
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
}
