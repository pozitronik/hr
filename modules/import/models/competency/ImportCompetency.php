<?php /** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\competency;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\core\traits\Upload;
use app\modules\import\models\competency\activerecord\ICUsers;
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
	private $domain;

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws BaseException
	 * @throws Throwable
	 */
	public function Import(string $filename, ?int $domain = null):bool {
		try {
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($filename);
			$spreadsheet->setActiveSheetIndex(0);
			$dataArray = $spreadsheet->getActiveSheet()->toArray();
		} catch (Throwable $t) {
			throw new BaseException('Формат файла не поддерживается');
		}
		$this->domain = $domain??time();
		$usersProcessed = 0;
		$userIdIndexes = [];
		$userScoreCellsIndexes = [];

		foreach ($dataArray as $rowIndex => $importRow) {
			if (0 === $rowIndex) {//Строченька с именами
				foreach ($importRow as $cellIndex => $cell) {
					if (0 === $cellIndex) continue;//в первой ячейке заголовок
					if (null !== $cell) {
						if (null !== $userId = $this->addICUser($cell)) {
							$userIdIndexes[] = $userId;//Запоминаем порядковые номера добавленных айдишников
							$userScoreCellsIndexes[$userId] = $cellIndex;//Запоминаем индексы колонок, в которых НАЧИНАЕТСЯ область оценок этого юзера
							$usersProcessed++;
						}
					}
				}
			}
			if (4 === $rowIndex) {//строчка с типами оценок
				/*Можно высчитывать автоматически, пока делаем константой*/
				$userScoreNamesIndexes = array_slice($importRow, 2, 6);//заголовки оценок, они всегда одинаковы
			}

			if ($rowIndex > 4) {
				if (null !== $newCompetencyName = ArrayHelper::getValue($importRow, '0')) {//сменилась компетенция
					$currentCompetencyName = $newCompetencyName;
				}
				if (null !== $newCompetencyFieldName = ArrayHelper::getValue($importRow, '1')) {//сменилось поле
					$currentCompetencyFieldName = $newCompetencyFieldName;
				}
				for ($usersCount = 0; $usersCount < $usersProcessed; $usersCount++) {
					$userScoreSliceBlock = array_slice($importRow, 2 + ($usersCount * 6), 6 + ($usersCount * 6));//вырезаем кусок оценок
					$this->addScores($userIdIndexes[$usersCount], $currentCompetencyName, $currentCompetencyFieldName, $userScoreNamesIndexes, $userScoreSliceBlock);
				}

			}
		}
		return true;
	}

	/**
	 * @param string $name
	 * @return int|null
	 */
	private function addICUser(string $name):?int {
		$name = trim($name);
		return ArrayHelper::getValue(ICUsers::addInstance(['name' => $name], [
			'name' => $name,//name
			'domain' => $this->domain
		]), 'id');

	}

	/**
	 * Добавляем оценки пользователю, при необходимости созздавая или модифицируя соответствующую компетенцию
	 * @param int $userId
	 * @param string $competencyName
	 * @param string $competencyFiled
	 * @param array $scoreNames
	 * @param array $scoreValues
	 * @return bool
	 */
	private function addScores(int $userId, string $competencyName, string $competencyFiled, array $scoreNames, array $scoreValues):bool {

	}

}