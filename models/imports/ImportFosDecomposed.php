<?php
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\groups\Groups;
use app\models\imports\fos\ImportFosChapter;
use app\models\imports\fos\ImportFosClusterProduct;
use app\models\imports\fos\ImportFosCommand;
use app\models\imports\fos\ImportFosDivisionLevel1;
use app\models\imports\fos\ImportFosDivisionLevel2;
use app\models\imports\fos\ImportFosDivisionLevel3;
use app\models\imports\fos\ImportFosDivisionLevel4;
use app\models\imports\fos\ImportFosDivisionLevel5;
use app\models\imports\fos\ImportFosFunctionalBlock;
use app\models\imports\fos\ImportFosFunctionalBlockTribe;
use app\models\imports\fos\ImportFosTribe;
use app\models\imports\fos\ImportFosUsers;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserPositions;
use app\models\relations\RelUsersAttributes;
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
		/*Должности
		*/
		//todo: в таблицы декомпозиции писать соответствия имеющимся данным
		/*Группы. Добавляем группу и её тип*/
		$data = ImportFosChapter::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosChapter $row */
			self::addGroup($row->name, 'Чаптер');
		}
		$data = ImportFosClusterProduct::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosClusterProduct $row */
			self::addGroup($row->name, 'Кластер');
		}
		$data = ImportFosCommand::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosCommand $row */
			self::addGroup($row->name, 'Команда');
		}
		$data = ImportFosDivisionLevel1::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel1 $row */
			self::addGroup($row->name, 'Подразделение первого уровня');
		}
		$data = ImportFosDivisionLevel2::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel2 $row */
			self::addGroup($row->name, 'Подразделение второго уровня');
		}
		$data = ImportFosDivisionLevel3::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel3 $row */
			self::addGroup($row->name, 'Подразделение третьего уровня');
		}
		$data = ImportFosDivisionLevel4::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel4 $row */
			self::addGroup($row->name, 'Подразделение четвёртого уровня');
		}
		$data = ImportFosDivisionLevel5::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosDivisionLevel5 $row */
			self::addGroup($row->name, 'Подразделение пятого уровня');
		}
		$data = ImportFosFunctionalBlock::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosFunctionalBlock $row */
			self::addGroup($row->name, 'Функциональный блок');
		}
		$data = ImportFosFunctionalBlockTribe::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosFunctionalBlockTribe $row */
			self::addGroup($row->name, 'Функциональный блок трайба');
		}
		$data = ImportFosTribe::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {
			/** @var ImportFosTribe $row */
			self::addGroup($row->name, 'Трайб');
		}
		/*Пользователи, с должностями, емайлами и атрибутами*/
		$data = ImportFosUsers::find()->where(['domain' => $domain])->all();
		foreach ($data as $row) {

			/** @var ImportFosUsers $row */
			self::addUser($row->name, $row->position_name, $row->email_alpha, [
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
					"value" => $row->town
				],
				[
					'attribute' => 'Адрес',
					'type' => 'string',
					'field' => 'Внешний почтовый адрес',
					"value" => $row->email_sigma
				]
			]);
		}
	}

	/**
	 * @param string $name
	 * @param string $position
	 * @param string $email
	 * @param array<array> $attributes
	 * @return int
	 * @throws Throwable
	 */
	public static function addUser(string $name, string $position, string $email, array $attributes = []):int {
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
		$user->createModel([
			'username' => $name,
			'login' => Utils::generateLogin(),
			'password' => Utils::gen_uuid(5),
			'salt' => null,
			'email' => ('' === $email)?Utils::generateLogin()."@localhost":$email,
			'deleted' => false
		]);
		$user->setAndSaveAttribute('position', $userPosition->id);

		foreach ($attributes as $attribute) {
			self::addAttributeProperty($attribute, $user->id);
		}
		return $user->id;
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
	 * @param array<string, string> $dynamic_attribute
	 * @param int $user_id
	 * @throws Throwable
	 * Добавляет атрибуту свойство
	 */
	public static function addAttributeProperty(array $dynamic_attribute, int $user_id):void {
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
}
