<?php
/** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\helpers\ArrayHelper;
use app\models\core\traits\Upload;
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
use yii\base\Exception as BaseException;
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
 * @property string $tribe_leader_tn Лидер трайба ТН
 * @property string $tribe_leader_name Лидер трайба
 * @property string $tribe_leader_it_tn IT-лидер трайба ТН
 * @property string $tribe_leader_it_name IT-лидер трайба
 * @property string $cluster_product_id Кластер/Продукт ID
 * @property string $cluster_product_code Код кластера/продукта
 * @property string $cluster_product_name Кластер/Продукт
 * @property string $cluster_product_leader_tn Лидер кластера/продукта ТН
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
 * @property string $chapter_leader_tn Лидер чаптера ТН
 * @property string $chapter_leader_name Лидер чаптера
 * @property string $chapter_couch_tn Agile-коуч ТН
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
			[['num', 'sd_id', 'position_name', 'user_tn', 'user_name', 'functional_block', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'remote_flag', 'town', 'functional_block_tribe', 'tribe_id', 'tribe_code', 'tribe_name', 'tribe_leader_tn', 'tribe_leader_name', 'tribe_leader_it_id', 'tribe_leader_it_name', 'cluster_product_id', 'cluster_product_code', 'cluster_product_name', 'cluster_product_leader_tn', 'cluster_product_leader_name', 'command_id', 'command_code', 'command_name', 'command_type', 'owner_name', 'command_position_id', 'command_position_code', 'command_position_name', 'chapter_id', 'chapter_code', 'chapter_name', 'chapter_leader_tn', 'chapter_leader_name', 'chapter_couch_tn', 'chapter_couch_name', 'email_sigma', 'email_alpha'], 'string', 'max' => 255]
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
			'user_tn' => 'ТН',
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
			'tribe_leader_tn' => 'Лидер трайба ТН',
			'tribe_leader_name' => 'Лидер трайба',
			'tribe_leader_it_tn' => 'IT-лидер трайба ТН',
			'tribe_leader_it_name' => 'IT-лидер трайба',
			'cluster_product_id' => 'Кластер/Продукт ID',
			'cluster_product_code' => 'Код кластера/продукта',
			'cluster_product_name' => 'Кластер/Продукт',
			'cluster_product_leader_tn' => 'Лидер кластера/продукта ТН',
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
			'chapter_leader_tn' => 'Лидер чаптера ТН',
			'chapter_leader_name' => 'Лидер чаптера',
			'chapter_couch_tn' => 'Agile-коуч ТН',
			'chapter_couch_name' => 'Agile-коуч',
			'email_sigma' => 'Адрес электронной почты (sigma)',
			'email_alpha' => 'Адрес электронной почты (внутренний)'
		];
	}

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws BaseException
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
			throw new BaseException('Формат файла не поддерживается');
		}
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
	 * @param array $messages Массив сообщений
	 * @return int текущий исполненный шаг
	 */
	public static function Decompose(int $domain, int $step = self::STEP_REFERENCES, array &$messages = []):int {
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		switch ($step) {
			case self::STEP_REFERENCES:

				foreach ($data as $row) {/*Декомпозируем справочные сущности: должность, город, позиция в команде. Таблицы декомпозиции не учитывают домен, наполняясь по мере новых импортов*/
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
						ImportFosUsers::addInstance(['user_tn' => $row->user_tn], [
							'user_tn' => $row->user_tn,
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
						$messages[] = ['row' => $row, 'error' => $importException->getName()];
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

						/*Для владельцев продукта приведены только имена; их может не быть*/
						if (null !== $product_owner_user = ImportFosUsers::find()->where(['name' => $row->owner_name])->one()) {
							ImportFosProductOwner::addInstance(['user_id' => $product_owner_user->id], [
								'user_id' => $product_owner_user->id,
								'domain' => $row->domain
							]);
						}

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
					} catch (ImportException $importException) {
						$messages[] = ['row' => $row, 'error' => $importException->getName()];
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_GROUPS:
				/*Декомпозируем сущности групп: функциональный блок, подразделения (5 уровней), функциональный блок трайба, трайб, кластер, команда, чаптер*/
				foreach ($data as $row) {
					try {

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
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->tribe_leader_tn], 'id'),
							'leader_it_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->tribe_leader_it_tn], 'id'),
							'domain' => $row->domain
						]);
						ImportFosClusterProduct::addInstance(['cluster_id' => $row->cluster_product_id], [
							'cluster_id' => $row->cluster_product_id,
							'name' => $row->cluster_product_name,
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->cluster_product_leader_tn], 'id'),
							'domain' => $row->domain
						]);
						ImportFosCommand::addInstance(['command_id' => $row->command_id], [
							'command_id' => $row->command_id,
							'name' => $row->command_name,
							'type' => $row->command_type,
							'code' => $row->command_code,
							'cluster_id' => ImportFosClusterProduct::findModelAttribute(['cluster_id' => $row->cluster_product_id], 'id'),
							'owner_id' => ImportFosUsers::findModelAttribute(['name' => $row->owner_name], 'id'),
							'domain' => $row->domain
						]);
						ImportFosChapter::addInstance(['chapter_id' => $row->chapter_id], [
							'chapter_id' => $row->chapter_id,
							'name' => $row->chapter_name,
							'code' => $row->chapter_code,
							'leader_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->chapter_leader_tn], 'id'),
							'couch_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->chapter_couch_tn], 'id'),
							'domain' => $row->domain
						]);
					} catch (ImportException $importException) {
						$messages[] = ['row' => $row, 'error' => $importException->getName()];
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
							'user_id' => ImportFosUsers::findModelAttribute(['user_tn' => $row->user_tn], 'id'),
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
