<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import;

use app\models\core\traits\Upload;
use app\modules\import\models\fos\ImportException;
use app\modules\targets\models\import\activerecord\ImportTargetsClusters;
use app\modules\targets\models\import\activerecord\ImportTargetsCommands;
use app\modules\targets\models\import\activerecord\ImportTargetsMilestones;
use app\modules\targets\models\import\activerecord\ImportTargetsSubinitiatives;
use app\modules\targets\models\import\activerecord\ImportTargetsTargets;
use app\modules\targets\models\references\RefTargetsResults;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Exception as BaseException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets".
 *
 * @property int $id
 * @property string|null $clusterName
 * @property string|null $commandName
 * @property string|null $commandCode
 * @property string|null $subInit
 * @property string|null $milestone
 * @property string|null $target
 * @property string|null $targetResult
 * @property string|null $resultValue
 * @property string|null $period
 * @property string|null $isYear
 * @property string|null $isLK
 * @property string|null $isLT
 * @property string|null $isCurator
 * @property string|null $comment
 * @property int domain
 */
class ImportTargets extends ActiveRecord {
	use Upload;

	public const STEP_REFERENCES = 0;
	public const STEP_GROUPS = 1;
	public const STEP_TARGETS = 2;
	public const STEP_FINISH = 3;
	public const LAST_STEP = self::STEP_FINISH + 1;

	public const step_labels = [
		self::STEP_REFERENCES => 'Декомпозиция справочных данных',
		self::STEP_TARGETS => 'Декомпозиция целей',
		self::STEP_GROUPS => 'Декомпозиция групп',
		self::STEP_FINISH => 'Итоговая сверка',
		self::LAST_STEP => 'Готово!'
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['clusterName', 'commandName', 'commandCode', 'subInit', 'milestone', 'target', 'targetResult', 'resultValue', 'period', 'isYear', 'isLK', 'isLT', 'isCurator', 'comment'], 'string', 'max' => 255],
			[['domain'], 'integer'],
			[['domain'], 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'clusterName' => 'Кластер',
			'commandName' => 'Команда',
			'commandCode' => 'Код команды',
			'subInit' => 'Субинициатива',
			'milestone' => 'Вехи',
			'target' => 'Цель',
			'targetResult' => 'Тип цели',
			'resultValue' => 'Величина метрики',
			'period' => 'Период',
			'isYear' => "Годовая\nда/нет",
			'isLK' => 'Цель ЛК?',
			'isLT' => 'Цель ЛТ?',
			'isCurator' => 'Цель у Куратора Блока?',
			'comment' => 'Комментарии',
		];
	}

	/**
	 * Проверяет, является ли массив заголовком таблицы
	 * @param array $row
	 * @return bool
	 * @throws Throwable
	 */
	private static function isHeaderRow(array $row):bool {
		return ArrayHelper::getValue($row, 0) === ArrayHelper::getValue((new self())->attributeLabels(), 'clusterName');
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
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($filename);
			$spreadsheet->setActiveSheetIndex(0);
			$dataArray = $spreadsheet->getActiveSheet()->toArray(null, false);
		} catch (Throwable $t) {
			throw new BaseException('Формат файла не поддерживается');
		}
		$domain = $domain??time();
		$labels = (new self())->attributeLabels();
		$keys = array_keys($labels);
		$headerProcessedFlag = false;
		$cKeys = count($keys);
		foreach ($dataArray as $importRow) {
			if ([] === array_filter($importRow)) continue;//ignore empty rows
			if (!$headerProcessedFlag && self::isHeaderRow($importRow)) {//однократно проверяем валидность таблицы
				$columnHeaderIndex = 0;
				foreach ($labels as $key => $value) {
					if ($value !== $headerValue = ArrayHelper::getValue($importRow, $columnHeaderIndex)) {
						throw new BaseException("Неожиданный формат файла импорта. Столбец {$columnHeaderIndex}, ожидается заголовок: {$value}, в файле: {$headerValue}.");
					}
					$columnHeaderIndex++;
				}
				$headerProcessedFlag = true;
				continue;
			}
			if (!$headerProcessedFlag) continue;
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
	 * @throws ImportException
	 */
	public static function Decompose(int $domain, int $step = self::STEP_REFERENCES, array &$messages = []):int {
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		foreach ($data as $row) {
			$currentTargetResult = RefTargetsResults::addInstance(['name' => $row->targetResult]);

			try {
				$cluster = ImportTargetsClusters::addInstance(['cluster_name' => $row->clusterName], [
					'name' => $row->clusterName,
//					'hr_group_id' => (null === $group = Groups::findModel(['name' => $row->clusterName]))?null:$group->id,
					'domain' => $row->domain
				]);
				$command = ImportTargetsCommands::addInstance(['command_id' => $row->commandCode], [
					'command_name' => $row->commandName,
					'command_id' => $row->commandCode,
//					'hr_group_id' => (null === $group = Groups::findModel(['name' => $row->commandName]))?null:$group->id,
					'domain' => $row->domain
				]);
				$subInitiative = ImportTargetsSubinitiatives::addInstance(['initiative' => $row->subInit], [
					'initiative' => $row->subInit,
					'domain' => $row->domain
				]);
				$milestone = ImportTargetsMilestones::addInstance(['milestone' => $row->milestone], [
					'milestone' => $row->milestone,
					'initiative_id' => ArrayHelper::getValue($subInitiative, 'id'),
					'domain' => $row->domain
				]);
				ImportTargetsTargets::addInstance(['target' => $row->target], [
					'target' => $row->target,
					'result_id' => ArrayHelper::getValue($currentTargetResult, 'id'),
					'value' => $row->resultValue,
					'period' => $row->period,
					'isYear' => 'Да' === $row->isYear,
					'isLK' => 'Да' === $row->isLK,
					'isLT' => 'Да' === $row->isLT,
					'isCurator' => 'Да' === $row->isCurator,
					'comment' => $row->comment,
					'milestone_id' => ArrayHelper::getValue($milestone, 'id'),
					'cluster_id' => ArrayHelper::getValue($cluster, 'id'),
					'command_id' => ArrayHelper::getValue($command, 'id'),
					'domain' => $row->domain
				]);
			} catch (ImportException $importException) {
				$messages[] = ['row' => $row, 'error' => $importException->getName()];
			} catch (Throwable $throwable) {
				$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
			}

		}
		return $step;
	}
}
