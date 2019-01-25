<?php /** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\competency;

use app\helpers\ArrayHelper;
use app\models\core\traits\Upload;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Throwable;
use yii\base\Model;
use yii\base\Exception as BaseException;

/**
 * Class ImportCompetency
 * @package app\modules\import\models\competency
 */
class ImportCompetency extends Model {
	use Upload;

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
		foreach ($dataArray as $importRow) {
			if (!is_numeric(ArrayHelper::getValue($importRow, "0"))) continue;//В первой ячейке строки должна быть цифра, если нет - это заголовок, его нужно пропустить
		}
		return true;
	}

}