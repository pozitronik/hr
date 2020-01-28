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
 * @property string|null $curator
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
			[['clusterName', 'commandName', 'commandCode', 'subInit', 'milestone', 'target', 'targetResult', 'resultValue', 'period', 'isYear', 'isLK', 'isLT', 'curator', 'comment'], 'string', 'max' => 255],
			[['domain'], 'integer'],
			[['domain'], 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'clusterName' => 'Cluster Name',
			'commandName' => 'Command Name',
			'commandCode' => 'Command Code',
			'subInit' => 'Sub Init',
			'milestone' => 'Milestone',
			'target' => 'Target',
			'targetResult' => 'Target Result',
			'resultValue' => 'Result Value',
			'period' => 'Period',
			'isYear' => 'Is Year',
			'isLK' => 'Is Lk',
			'isLT' => 'Is Lt',
			'curator' => 'Curator',
			'comment' => 'Comment',
			'domain' => 'domain'
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
			if (!$headerProcessedFlag && self::isHeaderRow($importRow)) {//однократно проверяем валидность таблицы
				$columnHeaderIndex = 0;
				foreach ($labels as $key => $value) {
					if ($value !== $headerValue = ArrayHelper::getValue($importRow, $columnHeaderIndex)) {
						throw new BaseException("Неожиданный формат файла импорта. Столбец {$columnHeaderIndex}, ожидается заголовок: {$value}, в файле: {$headerValue}.");
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
}
