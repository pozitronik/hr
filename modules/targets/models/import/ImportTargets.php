<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import;

use app\models\core\traits\Upload;
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
 */
class ImportTargets extends ActiveRecord {
	use Upload;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['clusterName', 'commandName', 'commandCode', 'subInit', 'milestone', 'target', 'targetResult', 'resultValue', 'period', 'isYear', 'isLK', 'isLT', 'isCurator', 'comment'], 'string', 'max' => 255],
			[['domain'], 'integer'],
			[['domain'], 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
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
}
