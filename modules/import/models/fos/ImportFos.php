<?php
/** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\modules\import\models\fos\activerecord\ImportFosClusterProductLeaderIt;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\components\pozitronik\helpers\ArrayHelper;
use app\components\pozitronik\core\traits\Upload;
use app\modules\import\models\fos\activerecord\ImportFosChapter;
use app\modules\import\models\fos\activerecord\ImportFosChapterCouch;
use app\modules\import\models\fos\activerecord\ImportFosChapterLeader;
use app\modules\import\models\fos\activerecord\ImportFosClusterProduct;
use app\modules\import\models\fos\activerecord\ImportFosClusterProductLeader;
use app\modules\import\models\fos\activerecord\ImportFosCommand;
use app\modules\import\models\fos\activerecord\ImportFosCommandPosition;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel1;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel2;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel3;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel4;
use app\modules\import\models\fos\activerecord\ImportFosDivisionLevel5;
use app\modules\import\models\fos\activerecord\ImportFosFunctionalBlock;
use app\modules\import\models\fos\activerecord\ImportFosFunctionalBlockTribe;
use app\modules\import\models\fos\activerecord\ImportFosPositions;
use app\modules\import\models\fos\activerecord\ImportFosProductOwner;
use app\modules\import\models\fos\activerecord\ImportFosTown;
use app\modules\import\models\fos\activerecord\ImportFosTribe;
use app\modules\import\models\fos\activerecord\ImportFosTribeLeader;
use app\modules\import\models\fos\activerecord\ImportFosTribeLeaderIt;
use app\modules\import\models\fos\activerecord\ImportFosUsers;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Throwable;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos".
 *
 * @property int $id
 * @property string $num № п/п
 * @property string $sd_id ШД ID
 * @property string $position_name Должность
 * @property string $user_tn ТН
 * @property string $user_name Ф.И.О. сотрудника
 * @property string $birthday Дата рождения
 * @property string $functional_block Функциональный блок
 * @property string $division_level_1 Подразделение 1 уровня
 * @property string $division_level_2 Подразделение 2 уровня
 * @property string $division_level_3 Подразделение 3 уровня
 * @property string $division_level_4 Подразделение 4 уровня
 * @property string $division_level_5 Подразделение 5 уровня
 * @property string $remote_flag Признак УРМ
 * @property string $town Населенный пункт
 * @property string $functional_block_tribe Функциональный блок трайба
 * @property string $tribe_id Трайб/Группа Agile команд ID
 * @property string $tribe_code Код трайба
 * @property string $tribe_name Трайб
 * @property string $tribe_leader_tn Лидер трайба ТН
 * @property string $tribe_leader_name Лидер трайба
 * @property string $tribe_leader_it_tn IT-лидер трайба ТН
 * @property string $tribe_leader_it_name IT-лидер трайба
 * @property string $cluster_product_id Кластер/Продукт ID
 * @property string $cluster_product_code Код кластера/продукта
 * @property string $cluster_product_name Кластер/Продукт
 * @property string $cluster_product_leader_tn Лидер кластера/продукта ТН
 * @property string $cluster_product_leader_name Лидер кластера/продукта
 *
 * @property string $cluster_product_leader_it_tn IT-лидер кластера/продукта ТН
 * @property string $cluster_product_leader_it_name IT-лидер кластера/продукта
 *
 * @property string $command_id Команда ID
 * @property string $command_code Код команды
 * @property string $command_name Команда
 * @property string $command_type Тип команды
 *
 * @property string $owner_tn ТН владельца продукта
 *
 * @property string $owner_name Владелец продукта
 * @property string $command_position_id Позиция в команде ID
 * @property string $command_position_code Код позиции в команде
 * @property string $command_position_name Позиция в команде
 *
 * @property string $expert_area Область экспертизы
 * @property string $combined_role Совмещаемая роль
 *
 * @property string $chapter_id Чаптер ID
 * @property string $chapter_code Код чаптера
 * @property string $chapter_name Чаптер
 * @property string $chapter_leader_tn Лидер чаптера ТН
 * @property string $chapter_leader_name Лидер чаптера
 * @property string $chapter_couch_tn Agile-коуч ТН
 * @property string $chapter_couch_name Agile-коуч
 * @property string $email_sigma Адрес электронной почты (sigma)
 * @property string $email_alpha Адрес электронной почты (внутренний
 * @property int $domain Служебная метка очереди импорта
 */
class ImportFos extends ActiveRecord {
	use Upload;

	//unused fields
	public $x1;
	public $x2;
	public $x3;
	public $x4;
	public $x5;

	public const STEP_REFERENCES = 0;
	public const STEP_USERS = 1;
	public const STEP_GROUPS = 2;
	public const STEP_FINISH = 3;
	public const LAST_STEP = self::STEP_FINISH + 1;

	public const step_labels = [
		self::STEP_REFERENCES => 'Декомпозиция справочных данных',
		self::STEP_USERS => 'Декомпозиция пользователей',
		self::STEP_GROUPS => 'Декомпозиция групп',
		self::STEP_FINISH => 'Итоговая сверка',
		self::LAST_STEP => 'Готово!'
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
			[['x1', 'x2', 'x3', 'x4', 'x5'], 'safe'],//unused
			[
				['num', 'sd_id', 'position_name', 'user_tn', 'user_name', 'birthday', 'functional_block', 'division_level_1', 'division_level_2',
					'division_level_3', 'division_level_4', 'division_level_5', 'remote_flag', 'town', 'functional_block_tribe', 'tribe_id',
					'tribe_code', 'tribe_name', 'tribe_leader_tn', 'tribe_leader_name', 'tribe_leader_it_id', 'tribe_leader_it_name', 'cluster_product_id',
					'cluster_product_code', 'cluster_product_name', 'cluster_product_leader_tn', 'cluster_product_leader_name', 'cluster_product_leader_it_tn', 'cluster_product_leader_it_name', 'command_id', 'command_code',
					'command_name', 'command_type', 'owner_tn', 'owner_name', 'command_position_id', 'command_position_code', 'command_position_name', 'expert_area', 'combined_role', 'chapter_id', 'chapter_code',
					'chapter_name', 'chapter_leader_tn', 'chapter_leader_name', 'chapter_couch_tn', 'chapter_couch_name', 'email_sigma', 'email_alpha'],
				'string', 'max' => 255]
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
			'x1' => 'Ставка должности',//10.09.2019 - новое, неиспользуемое поле
			'x2' => 'Заполнение',//10.09.2019 - новое, неиспользуемое поле
			'user_tn' => 'ТН',
			'user_name' => 'Ф.И.О. сотрудника',
			'birthday' => 'Дата рождения',
			'functional_block' => 'Блок 1',//Блок Розничный бизнес (Света)
			'division_level_1' => 'Блок 2',//Блок Дистрибуция (Писаренко)
			'division_level_2' => 'Дирекция',
			'division_level_3' => 'Департамент',
			'division_level_4' => 'Служба',
			'division_level_5' => 'Отдел',
			'remote_flag' => 'Признак УРМ',
			'town' => 'Населенный пункт',
			'functional_block_tribe' => 'Функциональный блок трайба',
			'tribe_id' => 'Трайб ID',
			'tribe_code' => 'Код трайба',
			'tribe_name' => 'Трайб',
			'x3' => 'Тип верхнеуровневого объекта Sbergile',//10.09.2019 - новое, неиспользуемое поле
			'tribe_leader_tn' => 'Лидер Блока 2 ТН',
			'tribe_leader_name' => 'Лидер Блока 2',
			'tribe_leader_it_tn' => 'Лидер Блока 1 ТН',//!
			'tribe_leader_it_name' => 'Лидер Блока 1',//!
			'cluster_product_id' => 'Кластер ID',
			'cluster_product_code' => 'Код кластера',
			'cluster_product_name' => 'Кластер',
			'x4' => 'Тип группировки команд',//10.09.2019 - новое, неиспользуемое поле
			'cluster_product_leader_tn' => 'Лидер дирекции ТН',
			'cluster_product_leader_name' => 'Лидер дирекции',
			'cluster_product_leader_it_tn' => 'IT-лидер кластера ТН',
			'cluster_product_leader_it_name' => 'IT-лидер кластера',
			'command_id' => 'Команда ID',
			'command_code' => 'Код команды',
			'command_name' => 'Команда',
			'command_type' => 'Тип команды',
			'owner_tn' => 'ТН владельца продукта',
			'owner_name' => 'Владелец продукта',
			'command_position_id' => 'Роль Sbergile ID',
			'command_position_code' => 'Код роли Sbergile',
			'command_position_name' => 'Роль Sbergile',
			'expert_area' => 'Область экспертизы',
			'combined_role' => 'Совмещаемая роль',
			'chapter_id' => 'Функциональная группа ID',
			'chapter_code' => 'Код функциональной группы',
			'chapter_name' => 'Функциональная группа',
			'chapter_leader_tn' => 'Team lead ТН',
			'chapter_leader_name' => 'Team lead',
			'chapter_couch_tn' => 'Agile-коуч ТН',
			'chapter_couch_name' => 'Agile-коуч',
			'email_sigma' => 'Адрес электронной почты (sigma)',
			'email_alpha' => 'Адрес электронной почты (внутренний)'
		];
	}

	/**
	 * Проверяет, является ли массив заголовком таблицы
	 * @param array $row
	 * @return bool
	 * @throws Throwable
	 */
	private static function isHeaderRow(array $row):bool {
		return ArrayHelper::getValue($row, 0) === ArrayHelper::getValue((new self())->attributeLabels(), 'num');
	}

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws Throwable
	 */
	public static function Import(string $filename, ?int $domain = null):bool {
		try {
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($filename);
			$spreadsheet->setActiveSheetIndex(0);
			$dataArray = $spreadsheet->getActiveSheet()->toArray();
		} catch (Throwable $t) {
			throw new Exception('Формат файла не поддерживается');
		}
		$domain = $domain??time();
		$labels = (new self())->attributeLabels();
		$keys = array_keys($labels);
		$headerProcessedFlag = false;
		$cKeys = count($keys);
		foreach ($dataArray as $importRow) {
			if (!$headerProcessedFlag && self::isHeaderRow($importRow)) {//однократно проверяем валидность таблицы
				$columnHeaderIndex = 0;
				foreach ($labels as $key => $value) {
					if ($value !== $headerValue = ArrayHelper::getValue($importRow, $columnHeaderIndex)) {
						throw new Exception("Неожиданный формат файла импорта. Столбец {$columnHeaderIndex}, ожидается заголовок: {$value}, в файле: {$headerValue}.");
					}
					$columnHeaderIndex++;
				}
				$headerProcessedFlag = true;
			}
			if (!is_numeric(ArrayHelper::getValue($importRow, "0"))) continue;//В первой ячейке строки должна быть цифра, если нет - это заголовок, его нужно пропустить
			$importRow = array_slice($importRow, 0, $cKeys);//в выгрузке может быть до хера пустых столбцов
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
	 * @param array $messages Массив сообщений
	 * @return int текущий исполненный шаг
	 * @throws Exception
	 * @throws InvalidConfigException
	 */
	public static function Decompose(int $domain, int $step = self::STEP_REFERENCES, array &$messages = []):int {
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		switch ($step) {
			case self::STEP_REFERENCES:

				foreach ($data as $row) {/*Декомпозируем справочные сущности: должность, город, позиция в команде. Таблицы декомпозиции не учитывают домен, наполняясь по мере новых импортов*/
					/*Прямое соответствие типа должности пользователя тому, в каком он фунциональном блоке находится. После расширения условий нужно будет дополнить разбор*/
					switch ($row->functional_block) {
						case 'Розничный бизнес':
							$positionTypeName = 'Бизнес';
						break;
						case 'Технологии':
							$positionTypeName = 'IT';
						break;
						default:
							$positionTypeName = null;
					}

//					$positionTypeName = ('Розничный бизнес' === $row->functional_block)?'Бизнес':('Технологии' === $row->functional_block)?'IT':null;
					$currentUserPositionType = RefUserPositionTypes::addInstance(['name' => $positionTypeName]);

					try {
						$position = ImportFosPositions::addInstance(['name' => $row->position_name], [
							'name' => $row->position_name,
							'domain' => $row->domain
						]);
						$town = ImportFosTown::addInstance(['name' => $row->town], [
							'name' => $row->town,
							'domain' => $row->domain
						]);
						ImportFosCommandPosition::addInstance(['position_id' => $row->command_position_id], [
							'position_id' => $row->command_position_id,
							'code' => $row->command_position_code,
							'name' => $row->command_position_name,
							'domain' => $row->domain
						]);
						/*Сразу же декомпозируем сущность сотрудника, т.к. тут соответствие 1=1*/
						ImportFosUsers::addInstance(['user_tn' => $row->user_tn, 'domain' => $row->domain], [
							'user_tn' => $row->user_tn,
							'name' => $row->user_name,
							'remote' => !empty($row->remote_flag),
							'email_sigma' => $row->email_sigma,
							'email_alpha' => $row->email_alpha,
							'sd_id' => $row->sd_id,
							'position_id' => ArrayHelper::getValue($position, 'id'),
							'town_id' => ArrayHelper::getValue($town, 'id'),
							'birthday' => $row->birthday,
							'expert_area' => $row->expert_area,
							'combined_role' => $row->combined_role,
							'domain' => $row->domain,
							'position_type' => ArrayHelper::getValue($currentUserPositionType, 'id')
						]);
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_USERS:

				/* Декомпозируем сущности остальных пользователей: лидер трайба, ИТ-лидер трайба, лидер кластера, владелец продукта (команды), лидер чаптера, коуч чаптера
			 * Предполагается, что их "пользователи" уже есть в таблице, иначе добавляем с тем, что нам известно.
			*/
				foreach ($data as $row) {
					try {

						$tribe_leader_user_id = ImportFosUsers::addInstance(['user_tn' => $row->tribe_leader_tn], [
							'user_tn' => $row->tribe_leader_tn,
							'name' => $row->tribe_leader_name,
							'remote' => false,
							'domain' => $row->domain
						])->id;

						ImportFosTribeLeader::addInstance(['user_id' => $tribe_leader_user_id], [
							'user_id' => $tribe_leader_user_id,
							'domain' => $row->domain
						]);

						$tribe_it_leader_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->tribe_leader_it_tn], [
							'user_tn' => $row->tribe_leader_it_tn,
							'name' => $row->tribe_leader_it_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosTribeLeaderIt::addInstance(['user_id' => $tribe_it_leader_user_id], [
							'user_id' => $tribe_it_leader_user_id,
							'domain' => $row->domain
						]);

						$cluster_product_leader_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->cluster_product_leader_tn], [
							'user_tn' => $row->cluster_product_leader_tn,
							'name' => $row->cluster_product_leader_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosClusterProductLeader::addInstance(['user_id' => $cluster_product_leader_user_id], [
							'user_id' => $cluster_product_leader_user_id,
							'domain' => $row->domain
						]);

						$cluster_product_leader_it_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->cluster_product_leader_it_tn], [
							'user_tn' => $row->cluster_product_leader_it_tn,
							'name' => $row->cluster_product_leader_it_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosClusterProductLeaderIt::addInstance(['user_id' => $cluster_product_leader_it_user_id], [
							'user_id' => $cluster_product_leader_it_user_id,
							'domain' => $row->domain
						]);

						$product_owner_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->owner_tn], [
							'user_tn' => $row->owner_tn,
							'name' => $row->owner_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosProductOwner::addInstance(['user_id' => $product_owner_user_id], [
							'user_id' => $product_owner_user_id,
							'domain' => $row->domain
						]);

						$chapter_leader_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->chapter_leader_tn], [
							'user_tn' => $row->chapter_leader_tn,
							'name' => $row->chapter_leader_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosChapterLeader::addInstance(['user_id' => $chapter_leader_user_id], [
							'user_id' => $chapter_leader_user_id,
							'domain' => $row->domain
						]);

						/*can be null instance, add handlers*/

						$chapter_couch_user_id = ArrayHelper::getValue(ImportFosUsers::addInstance(['user_tn' => $row->chapter_couch_tn], [
							'user_tn' => $row->chapter_couch_tn,
							'name' => $row->chapter_couch_name,
							'remote' => false,
							'domain' => $row->domain
						]), 'id');

						ImportFosChapterCouch::addInstance(['user_id' => $chapter_couch_user_id], [
							'user_id' => $chapter_couch_user_id,
							'domain' => $row->domain
						]);
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case
			self::STEP_GROUPS:
				/*Декомпозируем сущности групп: функциональный блок, подразделения (5 уровней), функциональный блок трайба, трайб, кластер, команда, чаптер*/
				foreach ($data as $row) {
					try {
						//todo: Для обновления данных групп просто включаем forceUpdate
						ImportFosFunctionalBlock::addInstance(['name' => $row->functional_block], [
							'name' => $row->functional_block,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel1::addInstance(['name' => $row->division_level_1], [
							'name' => $row->division_level_1,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel2::addInstance(['name' => $row->division_level_2], [
							'name' => $row->division_level_2,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel3::addInstance(['name' => $row->division_level_3], [
							'name' => $row->division_level_3,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel4::addInstance(['name' => $row->division_level_4], [
							'name' => $row->division_level_4,
							'domain' => $row->domain
						]);
						ImportFosDivisionLevel5::addInstance(['name' => $row->division_level_5], [
							'name' => $row->division_level_5,
							'domain' => $row->domain
						]);
						ImportFosFunctionalBlockTribe::addInstance(['name' => $row->functional_block_tribe], [
							'name' => $row->functional_block_tribe,
							'domain' => $row->domain
						]);

						ImportFosTribe::addInstance(['tribe_id' => $row->tribe_id], [
							'tribe_id' => $row->tribe_id,
							'code' => $row->tribe_code,
							'name' => $row->tribe_name,
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->tribe_leader_tn, 'domain' => $domain], 'id'),
							'leader_it_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->tribe_leader_it_tn, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);
						ImportFosClusterProduct::addInstance(['cluster_id' => $row->cluster_product_id], [
							'cluster_id' => $row->cluster_product_id,
							'name' => $row->cluster_product_name,
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->cluster_product_leader_tn, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);
						ImportFosCommand::addInstance(['command_id' => $row->command_id], [
							'command_id' => $row->command_id,
							'name' => $row->command_name,
							'type' => $row->command_type,
							'code' => $row->command_code,
							'cluster_id' => ImportFosClusterProduct::findModelAttribute(['cluster_id' => $row->cluster_product_id, 'domain' => $domain], 'id'),
							'owner_id' => ImportFosUsers::findModelAttribute(['name' => $row->owner_name, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);
						ImportFosChapter::addInstance(['chapter_id' => $row->chapter_id], [
							'chapter_id' => $row->chapter_id,
							'name' => $row->chapter_name,
							'code' => $row->chapter_code,
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->chapter_leader_tn, 'domain' => $domain], 'id'),
							'couch_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->chapter_couch_tn, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_FINISH:

				/* Подставляем айдишники декомпозированных сущностей в соответствующую таблицу */
				foreach ($data as $row) {
					try {
						$decomposedRow = new ImportFosDecomposed([
							'domain' => $row->domain,
							'position_id' => ImportFosPositions::findModelAttribute(['name' => $row->position_name], 'id'),
							'user_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->user_tn, 'domain' => $domain], 'id'),
							'functional_block_id' => ImportFosFunctionalBlock::findModelAttribute(['name' => $row->functional_block], 'id'),
							'division_level_1_id' => ImportFosDivisionLevel1::findModelAttribute(['name' => $row->division_level_1], 'id'),
							'division_level_2_id' => ImportFosDivisionLevel2::findModelAttribute(['name' => $row->division_level_2], 'id'),
							'division_level_3_id' => ImportFosDivisionLevel3::findModelAttribute(['name' => $row->division_level_3], 'id'),
							'division_level_4_id' => ImportFosDivisionLevel4::findModelAttribute(['name' => $row->division_level_4], 'id'),
							'division_level_5_id' => ImportFosDivisionLevel5::findModelAttribute(['name' => $row->division_level_5], 'id'),
							'functional_block_tribe_id' => ImportFosFunctionalBlockTribe::findModelAttribute(['name' => $row->functional_block_tribe], 'id'),
							'tribe_id' => ImportFosTribe::findModelAttribute(['tribe_id' => $row->tribe_id], 'id'),
							'cluster_product_id' => ImportFosClusterProduct::findModelAttribute(['cluster_id' => $row->cluster_product_id], 'id'),
							'command_id' => ImportFosCommand::findModelAttribute(['command_id' => $row->command_id], 'id'),
							'command_position_id' => ImportFosCommandPosition::findModelAttribute(['position_id' => $row->command_position_id], 'id'),
							'chapter_id' => ImportFosChapter::findModelAttribute(['chapter_id' => $row->chapter_id], 'id')
						]);
						$decomposedRow->save();
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
		}
		return $step;
	}

}
