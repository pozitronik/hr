<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import;

use app\models\core\traits\Upload;
use app\models\relations\RelGroupsGroups;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\import\models\ImportException;
use app\modules\targets\models\import\activerecord\ImportTargetsClusters;
use app\modules\targets\models\import\activerecord\ImportTargetsCommands;
use app\modules\targets\models\import\activerecord\ImportTargetsMilestones;
use app\modules\targets\models\import\activerecord\ImportTargetsSubinitiatives;
use app\modules\targets\models\import\activerecord\ImportTargetsTargets;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\relations\RelTargetsGroups;
use app\modules\targets\models\relations\RelTargetsTargets;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsIntervals;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Exception as BaseException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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

	public const STEP_START = 0;
	public const STEP_GROUPS = 1;
	public const STEP_LINKING_GROUPS = 2;
	public const STEP_TARGETS = 3;
	public const STEP_LINKING_TARGETS = 4;
	public const LAST_STEP = self::STEP_LINKING_TARGETS + 1;

	public const step_labels = [
		self::STEP_TARGETS => 'Декомпозиция целей',
		self::STEP_LINKING_TARGETS => 'Линковка целей',
		self::STEP_GROUPS => 'Декомпозиция групп',
		self::STEP_LINKING_GROUPS => 'Линковка групп',
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
				foreach ($labels as $value) {
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
	 * @param array $messages Массив сообщений
	 * @throws ImportException
	 */
	public static function Decompose(int $domain, array &$messages = []):void {
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		foreach ($data as $row) {
			$currentTargetResult = RefTargetsResults::addInstance(['name' => $row->targetResult]);

			try {
				$cluster = ImportTargetsClusters::addInstance(['cluster_name' => $row->clusterName], [
					'cluster_name' => $row->clusterName,
//					'hr_group_id' => (null === $group = Groups::findModel(['name' => $row->clusterName]))?null:$group->id,
					'domain' => $row->domain
				]);
				if (is_numeric($row->commandCode)) {
					$command = ImportTargetsCommands::addInstance(['command_id' => $row->commandCode], [
						'command_name' => $row->commandName,
						'command_id' => $row->commandCode,
//					'hr_group_id' => (null === $group = Groups::findModel(['name' => $row->commandName]))?null:$group->id,
						'domain' => $row->domain
					]);
				} else {
					$command = null;
				}
				$subInitiative = ImportTargetsSubinitiatives::addInstance(['initiative' => $row->subInit], [
					'initiative' => $row->subInit,
					'domain' => $row->domain
				]);
				$milestone = ImportTargetsMilestones::addInstance(['milestone' => $row->milestone], [
					'milestone' => $row->milestone,
					'initiative_id' => ArrayHelper::getValue($subInitiative, 'id'),
					'domain' => $row->domain
				]);
				/*Данные не требуют такой жёсткой декомпозиции, как ФОС, поэтому разобранные данные более-менее будут находиться здесь*/
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
					'command_id' => null === $command?null:ArrayHelper::getValue($command, 'id'),
					'domain' => $row->domain
				]);
			} catch (ImportException $importException) {
				$messages[] = ['row' => $row, 'error' => $importException->getName()];
			} catch (Throwable $throwable) {
				$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
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
	 * @param string $name
	 * @param string $type
	 * @param int|null $result_type_id
	 * @return int
	 */
	public static function addTarget(string $name, string $type, ?int $result_type_id = null):int {
		if (empty($name)) return -1;

		$targetType = RefTargetsTypes::find()->where(['name' => $type])->one();
		if (!$targetType) {
			$targetType = new RefTargetsTypes(['name' => $type]);
			$targetType->save();
		}

		$target = Targets::find()->where(['name' => $name, 'type' => $targetType->id])->one();
		if ($target) return $target->id;

		$target = new Targets();
		$target->createModel([
			'name' => mb_substr($name, 0, 512),
			'type' => $targetType->id,
			'result_type' => $result_type_id,
			'comment' => $name,
			'deleted' => false
		], false);
		return $target->id;
	}

	/**
	 * Разбираем декомпозированные данные и вносим в боевую таблицу
	 * @param int $step
	 * @return bool true - шаг выполнен, false - нужно повторить запрос (шаг разбит на подшаги)
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public static function ImportToDB(int $step = self::STEP_GROUPS):bool {//todo: добавить учёт домена для скорости?
		/*Идём по таблицам декомпозиции, добавляя данные из них в соответствующие таблицы структуры*/
		switch ($step) {
			case self::STEP_GROUPS:/*Группы. Добавляем группу и её тип*/
				foreach (ImportTargetsClusters::findAll(['hr_group_id' => null]) as $cluster) {
					/** @var ImportTargetsClusters $cluster */
					$cluster->setAndSaveAttribute('hr_group_id', self::addGroup($cluster->cluster_name, 'Кластер'));
				}
				foreach (ImportTargetsCommands::findAll(['hr_group_id' => null]) as $command) {
					/** @var ImportTargetsCommands $command */
					$command->setAndSaveAttribute('hr_group_id', self::addGroup($command->command_name, 'Команда'));
				}
			break;
			case self::STEP_LINKING_GROUPS:
				foreach (ImportTargetsCommands::find()->all() as $command) {
					/** @var ImportTargetsCommands $command */
					foreach ($command->getRelCluster()->all() as $cluster) {
						/** @var ImportTargetsCommands $command */
						RelGroupsGroups::linkModels($command->hr_group_id, $cluster->hr_group_id);
					}
				}
			break;
			case self::STEP_TARGETS:
				foreach (ImportTargetsSubinitiatives::findAll(['hr_target_id' => null]) as $subInitiative) {
					$subInitiative->setAndSaveAttribute('hr_target_id', self::addTarget($subInitiative->initiative, 'Субинициатива'));
				}
				foreach (ImportTargetsMilestones::findAll(['hr_target_id' => null]) as $milestone) {
					$milestone->setAndSaveAttribute('hr_target_id', self::addTarget($milestone->milestone, 'Веха'));
				}
				foreach (ImportTargetsTargets::findAll(['hr_target_id' => null]) as $target) {
					$target->setAndSaveAttribute('hr_target_id', $hrTargetId = self::addTarget($target->target, 'Цель', $target->result_id));
					TargetsIntervals::fromFilePeriod($target->period, $hrTargetId);
				}
			break;
			case self::STEP_LINKING_TARGETS:
				foreach (ImportTargetsMilestones::find()->all() as $milestone) {
					/** @var ImportTargetsMilestones $milestone */
					RelTargetsTargets::linkModels($milestone->relSubInitiatives->hr_target_id, $milestone->hr_target_id);
				}

				foreach (ImportTargetsTargets::find()->all() as $target) {
					/** @var ImportTargetsTargets $target */
					RelTargetsTargets::linkModels($target->relMilestones->hr_target_id, $target->hr_target_id);
					RelTargetsGroups::linkModels($target->hr_target_id, $target->relCommands->hr_group_id);
				}

			break;
			default:
				throw new NotFoundHttpException('Step not found');
			break;
		}
		return true;

	}
}
