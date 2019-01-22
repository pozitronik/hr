<?php
/** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\ArrayHelper;
use app\models\core\traits\Upload;
use app\models\imports\fos\ImportFosChapter;
use app\models\imports\fos\ImportFosChapterCouch;
use app\models\imports\fos\ImportFosChapterLeader;
use app\models\imports\fos\ImportFosClusterProduct;
use app\models\imports\fos\ImportFosClusterProductLeader;
use app\models\imports\fos\ImportFosCommand;
use app\models\imports\fos\ImportFosCommandPosition;
use app\models\imports\fos\ImportFosDivisionLevel1;
use app\models\imports\fos\ImportFosDivisionLevel2;
use app\models\imports\fos\ImportFosDivisionLevel3;
use app\models\imports\fos\ImportFosDivisionLevel4;
use app\models\imports\fos\ImportFosDivisionLevel5;
use app\models\imports\fos\ImportFosFunctionalBlock;
use app\models\imports\fos\ImportFosFunctionalBlockTribe;
use app\models\imports\fos\ImportFosPositions;
use app\models\imports\fos\ImportFosProductOwner;
use app\models\imports\fos\ImportFosTown;
use app\models\imports\fos\ImportFosTribe;
use app\models\imports\fos\ImportFosTribeLeader;
use app\models\imports\fos\ImportFosTribeLeaderIt;
use app\models\imports\fos\ImportFosUsers;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Throwable;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos".
 *
 * @property int $id
 * @property string $num № п/п
 * @property string $sd_id ШД ID
 * @property string $position_name Должность
 * @property string $user_id ТН
 * @property string $user_name Ф.И.О. сотрудника
 * @property string $functional_block Функциональный блок
 * @property string $division_level_1 Подразделение 1 уровня
 * @property string $division_level_2 Подразделение 2 уровня
 * @property string $division_level_3 Подразделение 3 уровня
 * @property string $division_level_4 Подразделение 4 уровня
 * @property string $division_level_5 Подразделение 5 уровня
 * @property string $remote_flag Признак УРМ
 * @property string $town Населенный пункт
 * @property string $functional_block_tribe Функциональный блок трайба
 * @property string $tribe_id Трайб ID
 * @property string $tribe_code Код трайба
 * @property string $tribe_name Трайб
 * @property string $tribe_leader_id Лидер трайба ТН
 * @property string $tribe_leader_name Лидер трайба
 * @property string $tribe_leader_it_id IT-лидер трайба ТН
 * @property string $tribe_leader_it_name IT-лидер трайба
 * @property string $cluster_product_id Кластер/Продукт ID
 * @property string $cluster_product_code Код кластера/продукта
 * @property string $cluster_product_name Кластер/Продукт
 * @property string $cluster_product_leader_id Лидер кластера/продукта ТН
 * @property string $cluster_product_leader_name Лидер кластера/продукта
 * @property string $command_id Команда ID
 * @property string $command_code Код команды
 * @property string $command_name Команда
 * @property string $command_type Тип команды
 * @property string $owner_name Владелец продукта
 * @property string $command_position_id Позиция в команде ID
 * @property string $command_position_code Код позиции в команде
 * @property string $command_position_name Позиция в команде
 * @property string $chapter_id Чаптер ID
 * @property string $chapter_code Код чаптера
 * @property string $chapter_name Чаптер
 * @property string $chapter_leader_id Лидер чаптера ТН
 * @property string $chapter_leader_name Лидер чаптера
 * @property string $chapter_couch_id Agile-коуч ТН
 * @property string $chapter_couch_name Agile-коуч
 * @property string $email_sigma Адрес электронной почты (sigma)
 * @property string $email_alpha Адрес электронной почты (внутренний
 * @property int $domain Служеная метка очереди импорта
 */
class ImportFos extends ActiveRecord {
	use Upload;

	public const STEP_REFERENCES = 0;
	public const STEP_USERS = 1;
	public const STEP_GROUPS = 2;
	public const STEP_FINISH = 3;

	public const step_labels = [
		self::STEP_REFERENCES => 'Декомпозиция справочных данных',
		self::STEP_USERS => 'Декомпозиция пользователей',
		self::STEP_GROUPS => 'Декомпозиция групп',
		self::STEP_FINISH => 'Итоговая сверка'
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['domain'], 'integer'],
			[['num', 'sd_id', 'position_name', 'user_id', 'user_name', 'functional_block', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'remote_flag', 'town', 'functional_block_tribe', 'tribe_id', 'tribe_code', 'tribe_name', 'tribe_leader_id', 'tribe_leader_name', 'tribe_leader_it_id', 'tribe_leader_it_name', 'cluster_product_id', 'cluster_product_code', 'cluster_product_name', 'cluster_product_leader_id', 'cluster_product_leader_name', 'command_id', 'command_code', 'command_name', 'command_type', 'owner_name', 'command_position_id', 'command_position_code', 'command_position_name', 'chapter_id', 'chapter_code', 'chapter_name', 'chapter_leader_id', 'chapter_leader_name', 'chapter_couch_id', 'chapter_couch_name', 'email_sigma', 'email_alpha'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [/*Не модифицировать, а то поедет импорт!*/
			'num' => '№ п/п',
			'sd_id' => 'ШД ID',//!Это не ID должности! Это какойто внутренний идентификатор
			'position_name' => 'Должность',
			'user_id' => 'ТН',
			'user_name' => 'Ф.И.О. сотрудника',
			'functional_block' => 'Функциональный блок',
			'division_level_1' => 'Подразделение 1 уровня',
			'division_level_2' => 'Подразделение 2 уровня',
			'division_level_3' => 'Подразделение 3 уровня',
			'division_level_4' => 'Подразделение 4 уровня',
			'division_level_5' => 'Подразделение 5 уровня',
			'remote_flag' => 'Признак УРМ',
			'town' => 'Населенный пункт',
			'functional_block_tribe' => 'Функциональный блок трайба',
			'tribe_id' => 'Трайб ID',
			'tribe_code' => 'Код трайба',
			'tribe_name' => 'Трайб',
			'tribe_leader_id' => 'Лидер трайба ТН',
			'tribe_leader_name' => 'Лидер трайба',
			'tribe_leader_it_id' => 'IT-лидер трайба ТН',
			'tribe_leader_it_name' => 'IT-лидер трайба',
			'cluster_product_id' => 'Кластер/Продукт ID',
			'cluster_product_code' => 'Код кластера/продукта',
			'cluster_product_name' => 'Кластер/Продукт',
			'cluster_product_leader_id' => 'Лидер кластера/продукта ТН',
			'cluster_product_leader_name' => 'Лидер кластера/продукта',
			'command_id' => 'Команда ID',
			'command_code' => 'Код команды',
			'command_name' => 'Команда',
			'command_type' => 'Тип команды',
			'owner_name' => 'Владелец продукта',
			'command_position_id' => 'Позиция в команде ID',
			'command_position_code' => 'Код позиции в команде',
			'command_position_name' => 'Позиция в команде',
			'chapter_id' => 'Чаптер ID',
			'chapter_code' => 'Код чаптера',
			'chapter_name' => 'Чаптер',
			'chapter_leader_id' => 'Лидер чаптера ТН',
			'chapter_leader_name' => 'Лидер чаптера',
			'chapter_couch_id' => 'Agile-коуч ТН',
			'chapter_couch_name' => 'Agile-коуч',
			'email_sigma' => 'Адрес электронной почты (sigma)',
			'email_alpha' => 'Адрес электронной почты (внутренний)'
		];
	}

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws PhpSpreadsheetException
	 * @throws Exception
	 * @throws Throwable
	 */
	public static function Import(string $filename, ?int $domain = null):bool {
		$reader = new Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($filename);
		$spreadsheet->setActiveSheetIndex(0);
		$dataArray = $spreadsheet->getActiveSheet()->toArray();
		$domain = $domain??time();
		$keys = array_keys((new self())->attributeLabels());
		foreach ($dataArray as $importRow) {
			if (!is_numeric(ArrayHelper::getValue($importRow, "0"))) continue;//В первой ячейке строки должна быть цифра, если нет - это заголовок, его нужно пропустить
			$data = array_combine($keys, $importRow);

			$row = new self($data);
			$row->domain = $domain;
			$row->save(false);//пока сохраняем без строгой валидации
		}
		return true;
	}

	/**
	 * Анализирует проведённый импорт, декомпозируя данные по таблицам и генерируя сводную таблицу импорта
	 * @param int $domain
	 * @param int $step
	 * @return array Массив сообщений
	 */
	public static function Decompose(int $domain, int $step = 0):array {
		$errors = [];
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		switch ($step) {
			case self::STEP_REFERENCES:

				foreach ($data as $row) {/*Декомпозируем справочные сущности: должность, город, позиция в команде*/
					try {
						$position = ImportFosPositions::addInstance(['name' => $row->position_name, 'domain' => $domain], [
							'name' => $row->position_name,
							'domain' => $row->domain
						]);
						$town = ImportFosTown::addInstance([
							'name' => $row->town,
							'domain' => $row->domain
						]);
						ImportFosCommandPosition::addInstance(['id' => $row->command_position_id, 'domain' => $domain], [
							'id' => $row->command_position_id,
							'code' => $row->command_position_code,
							'name' => $row->command_position_name,
							'domain' => $row->domain
						]);
						/*Сразу же декомпозируем сущность сотрудника, т.к. тут соответствие 1=1*/
						ImportFosUsers::addInstance(['id' => $row->user_id, 'domain' => $domain], [
							'id' => $row->user_id,
							'name' => $row->user_name,
							'remote' => !empty($row->remote_flag),
							'email_sigma' => $row->email_sigma,
							'email_alpha' => $row->email_alpha,
							'sd_id' => $row->sd_id,
							'position_id' => ArrayHelper::getValue($position, 'id'),
							'town_id' => ArrayHelper::getValue($town, 'id'),
							'domain' => $row->domain
						]);
					} catch (ImportException $importException) {
						$errors[] = ['row' => $row, 'error' => $importException->getName()];
					} catch (Throwable $throwable) {
						$errors[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_USERS:

				/* Декомпозируем сущности остальных пользователей: лидер трайба, ИТ-лидер трайба, лидер кластера, владелец продукта (команды), лидер чаптера, коуч чаптера
			 * Предполагается, что их "пользователи" уже есть в таблице, иначе добавляем с тем, что нам известно.
			*/
				foreach ($data as $row) {
					try {
						ImportFosTribeLeader::addInstance(['user_id' => $row->tribe_leader_id, 'domain' => $domain], [
							'user_id' => ImportFosUsers::addInstance($row->tribe_leader_id, [
								'id' => $row->tribe_leader_id,
								'name' => $row->tribe_leader_name,
								'remote' => false,
								'domain' => $row->domain
							])->id,
							'domain' => $row->domain
						]);

						ImportFosTribeLeaderIt::addInstance(['user_id' => $row->tribe_leader_it_id, 'domain' => $domain], [
							'user_id' => ArrayHelper::getValue(ImportFosUsers::addInstance($row->tribe_leader_it_id, [
								'id' => $row->tribe_leader_it_id,
								'name' => $row->tribe_leader_it_name,
								'remote' => false,
								'domain' => $row->domain
							]), 'id'),
							'domain' => $row->domain
						]);

						ImportFosClusterProductLeader::addInstance(['user_id' => $row->cluster_product_leader_id, 'domain' => $domain], [
							'user_id' => ArrayHelper::getValue(ImportFosUsers::addInstance($row->cluster_product_leader_id, [
								'id' => $row->cluster_product_leader_id,
								'name' => $row->cluster_product_leader_name,
								'remote' => false,
								'domain' => $row->domain
							]), 'id'),
							'domain' => $row->domain
						]);

						/*Для владельцев продукта приведены только имена; их может не быть*/
						if (null !== $product_owner = ImportFosUsers::find()->where(['name' => $row->owner_name, 'domain' => $domain])->one()) {
							ImportFosProductOwner::addInstance(['user_id' => $product_owner->id], [
								'user_id' => $product_owner->id,
								'domain' => $row->domain
							]);
						}

						ImportFosChapterLeader::addInstance(['user_id' => $row->chapter_leader_id, 'domain' => $domain], [
							'user_id' => ArrayHelper::getValue(ImportFosUsers::addInstance($row->chapter_leader_id, [
								'id' => $row->chapter_leader_id,
								'name' => $row->chapter_leader_name,
								'remote' => false,
								'domain' => $row->domain
							]), 'id'),
							'domain' => $row->domain
						]);
						/*can be null instance, add handlers*/
						ImportFosChapterCouch::addInstance(['user_id' => $row->chapter_couch_id, 'domain' => $domain], [
							'user_id' => ArrayHelper::getValue(ImportFosUsers::addInstance($row->chapter_couch_id, [
								'id' => $row->chapter_couch_id,
								'name' => $row->chapter_couch_name,
								'remote' => false,
								'domain' => $row->domain
							]), 'id'),
							'domain' => $row->domain
						]);
					} catch (ImportException $importException) {
						$errors[] = ['row' => $row, 'error' => $importException->getName()];
					} catch (Throwable $throwable) {
						$errors[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_GROUPS:
				/*Декомпозируем сущности групп: функциональный блок, подразделения (5 уровней), функциональный блок трайба, трайб, кластер, команда, чаптер*/
				foreach ($data as $row) {
					try {

						ImportFosFunctionalBlock::addInstance(['name' => $row->functional_block, 'domain' => $domain], [
							'name' => $row->functional_block,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel1::addInstance(['name' => $row->division_level_1, 'domain' => $domain], [
							'name' => $row->division_level_1,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel2::addInstance(['name' => $row->division_level_2, 'domain' => $domain], [
							'name' => $row->division_level_2,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel3::addInstance(['name' => $row->division_level_3, 'domain' => $domain], [
							'name' => $row->division_level_3,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel4::addInstance(['name' => $row->division_level_4, 'domain' => $domain], [
							'name' => $row->division_level_4,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel5::addInstance(['name' => $row->division_level_5, 'domain' => $domain], [
							'name' => $row->division_level_5,
							'domain' => $row->domain
						]);
						ImportFosFunctionalBlockTribe::addInstance(['name' => $row->functional_block_tribe, 'domain' => $domain], [
							'name' => $row->functional_block_tribe,
							'domain' => $row->domain
						]);
						ImportFosTribe::addInstance(['id' => $row->tribe_id, 'domain' => $domain], [
							'id' => $row->tribe_id,
							'code' => $row->tribe_code,
							'name' => $row->tribe_name,
							'leader_id' => ImportFosTribeLeader::findModelAttribute(['user_id' => $row->tribe_leader_id]),
							'leader_it_id' => ImportFosTribeLeaderIt::findModelAttribute(['user_id' => $row->tribe_leader_it_id]),
							'domain' => $row->domain
						]);
						ImportFosClusterProduct::addInstance(['id' => $row->cluster_product_id, 'domain' => $domain], [
							'id' => $row->cluster_product_id,
							'name' => $row->cluster_product_name,
							'leader_id' => ImportFosClusterProductLeader::findModelAttribute(['user_id' => $row->cluster_product_leader_id]),
							'domain' => $row->domain
						]);
						ImportFosCommand::addInstance(['id' => $row->command_id, 'domain' => $domain], [
							'id' => $row->command_id,
							'name' => $row->command_name,
							'type' => $row->command_type,
							'code' => $row->command_code,
							'cluster_id' => ImportFosClusterProduct::findModelAttribute($row->cluster_product_id),
							'owner_id' => ImportFosProductOwner::findModelAttribute(['user_id' => ImportFosUsers::findModelAttribute(['name' => $row->owner_name])]),
							'domain' => $row->domain
						]);
						ImportFosChapter::addInstance(['id' => $row->chapter_id, 'domain' => $domain], [
							'id' => $row->chapter_id,
							'name' => $row->chapter_name,
							'code' => $row->chapter_code,
							'leader_id' => ImportFosChapterLeader::findModelAttribute(['user_id' => $row->chapter_leader_id]),
							'couch_id' => ImportFosChapterCouch::findModelAttribute(['user_id' => $row->chapter_couch_id]),
							'domain' => $row->domain
						]);
					} catch (ImportException $importException) {
						$errors[] = ['row' => $row, 'error' => $importException->getName()];
					} catch (Throwable $throwable) {
						$errors[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_FINISH:

				/* Подставляем айдишники декомпозированных сущностей в соответствующую таблицу */
				foreach ($data as $row) {
					try {
						$decomposedRow = new ImportFosDecomposed([
							'domain' => $row->domain,
							'position_id' => ImportFosPositions::findModelAttribute(['name' => $row->position_name]),
							'user_id' => ImportFosUsers::findModelAttribute($row->user_id),
							'functional_block' => ImportFosFunctionalBlock::findModelAttribute(['name' => $row->functional_block]),
							'division_level_1' => ImportFosDivisionLevel1::findModelAttribute(['name' => $row->division_level_1]),
							'division_level_2' => ImportFosDivisionLevel2::findModelAttribute(['name' => $row->division_level_2]),
							'division_level_3' => ImportFosDivisionLevel3::findModelAttribute(['name' => $row->division_level_3]),
							'division_level_4' => ImportFosDivisionLevel4::findModelAttribute(['name' => $row->division_level_4]),
							'division_level_5' => ImportFosDivisionLevel5::findModelAttribute(['name' => $row->division_level_5]),
							'functional_block_tribe' => ImportFosFunctionalBlockTribe::findModelAttribute(['name' => $row->functional_block_tribe]),
							'tribe_id' => ImportFosTribe::findModelAttribute($row->tribe_id),
							'cluster_product_id' => ImportFosClusterProduct::findModelAttribute($row->cluster_product_id),
							'command_id' => ImportFosCommand::findModelAttribute($row->command_id),
							'command_position_id' => ImportFosCommandPosition::findModelAttribute($row->command_position_id),
							'chapter_id' => ImportFosChapter::findModelAttribute($row->chapter_id)
						]);
						$decomposedRow->save();
					} catch (Throwable $throwable) {
						$errors[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
		}
		return $errors;
	}

}
