<?php /** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\competency;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\core\traits\Upload;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\relations\RelUsersAttributes;
use app\models\users\Users;
use app\modules\import\models\competency\activerecord\ICAttributes;
use app\modules\import\models\competency\activerecord\ICFields;
use app\modules\import\models\competency\activerecord\ICRelUsersFields;
use app\modules\import\models\competency\activerecord\ICUsers;
use app\modules\import\models\fos\ImportException;
use http\Exception\RuntimeException;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Throwable;
use yii\base\Model;
use yii\base\Exception as BaseException;
use yii\db\Exception;

/**
 * Class ImportCompetency
 * @package app\modules\import\models\competency
 */
class ImportCompetency extends Model {
	use Upload;
	private $domain;

	/**
	 * Декомпозирует загруженный файл в таблички
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws BaseException
	 * @throws Throwable
	 */
	public function Decompose(string $filename, ?int $domain = null):bool {
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
		$userScoreNamesIndexes = [];
		$currentCompetencyFieldName = '';
		$currentCompetencyName = '';
		//	$userScoreCellsIndexes = [];

		/** @var array $dataArray */
		foreach ($dataArray as $rowIndex => $importRow) {
			if (0 === $rowIndex) {//Строченька с именами
				/** @var array $importRow */
				foreach ($importRow as $cellIndex => $cell) {
					if (0 === $cellIndex) continue;//в первой ячейке заголовок
					if ((null !== $cell) && null !== $userId = $this->addICUser($cell)) {
						$userIdIndexes[] = $userId;//Запоминаем порядковые номера добавленных айдишников
						$userScoreCellsIndexes[$userId] = $cellIndex;//Запоминаем индексы колонок, в которых НАЧИНАЕТСЯ область оценок этого юзера
						$usersProcessed++;
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
				foreach ($userIdIndexes as $usersCount => $usersCountValue) {
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
	 * @throws ImportException
	 * @throws Throwable
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
	 * @param string $competencyField
	 * @param array $scoreNames
	 * @param array $scoreValues
	 * @return bool
	 * @throws ImportException
	 * @throws Throwable
	 */
	private function addScores(int $userId, string $competencyName, string $competencyField, array $scoreNames, array $scoreValues):bool {
		if (null !== $competencyFieldId = $this->addCompetencyField($competencyName, $competencyField)) {
			return $this->addScoreValues($userId, $competencyFieldId, $scoreNames, $scoreValues);
		}
		throw new RuntimeException("Сбой добавления оценки {$competencyName}:{$competencyField}");

	}

	/**
	 * Добавляет/находит компетенцию и поле, возвращая айдишник поля
	 * @param string $competencyName
	 * @param string $competencyField
	 * @return int|null
	 * @throws Throwable
	 * @throws ImportException
	 */
	private function addCompetencyField(string $competencyName, string $competencyField):?int {
		$competencyId = ArrayHelper::getValue(ICAttributes::addInstance(['name' => $competencyName], [
			'name' => $competencyName,
			'domain' => $this->domain
		]), 'id');

		return ArrayHelper::getValue(ICFields::addInstance(['attribute_id' => $competencyId, 'name' => $competencyField], [
			'attribute_id' => $competencyId,
			'name' => $competencyField,
			'domain' => $this->domain
		]), 'id');
	}

	/**
	 * Добавляет сериализованный набор оценок пользователю
	 * @param int $userId
	 * @param int $fieldId
	 * @param array $scoreNames
	 * @param array $scoreValues
	 * @return bool
	 * @throws ImportException
	 * @throws Throwable
	 */
	private function addScoreValues(int $userId, int $fieldId, array $scoreNames, array $scoreValues):bool {

		/*$scoreData = [];
		foreach ($scoreNames as $index => $name) {//Строим структуру оценки, которую схороним в сериализованном виде. Такой способ позволяет избежать коллизий в именах оценок
			$scoreData[] = [$name => ArrayHelper::getValue($scoreValues, $index)];
		}*/

		if (null !== ICRelUsersFields::addInstance(['user_id' => $userId, 'field_id' => $fieldId], [
				'user_id' => $userId,
				'field_id' => $fieldId,
				'value' => json_encode($scoreValues, JSON_UNESCAPED_UNICODE),
				'domain' => $this->domain
			])) return true;
		return false;
	}

	/**
	 * Проходим по декомпозированным таблицам и добавляем данные в боевую БД
	 */
	public function Import():array {
		$result = [];
		$allUsers = ICUsers::findAll(['hr_user_id' => null]);//Взяли всех необработанных юзеров
		foreach ($allUsers as $ICUser) {
			/** @var Users[] $usersFound */
			$usersFound = Users::find()->where(['username' => $ICUser->name])->all();
			$countUsersFound = count($usersFound);
			if (0 === $countUsersFound) {
				$result[] = "Пользователь {$ICUser->name} не найден в БД, пропускаю";//todo: создаём юзера с добавлением в неопознанную группу
			} elseif ($countUsersFound > 1) {
				$result[] = "Найдено ".Utils::pluralForm($countUsersFound, ['пользователь', 'пользователя', 'пользователей'])."с именем {$ICUser->name}, пропускаю";
			} else {//сопоставили пользователя
				$user = $usersFound[0];
				$allUserScores = ICRelUsersFields::findAll(['user_id' => $ICUser->id]);
				foreach ($allUserScores as $score) {
					$this->addUserProperty($user->id, $score->relAttribute->name, $score->relField->name, $score->value);
				}
				$ICUser->setAndSaveAttribute('hr_user_id', $user->id);
			}
		}
		return $result;
	}

	/**
	 * @param int $user_id
	 * @param string $attributeName
	 * @param string $attributeFieldName
	 * @param string $attributeFieldValue
	 * @param string $fieldType
	 * @throws Throwable
	 * @throws Exception
	 */
	private function addUserProperty(int $user_id, string $attributeName, string $attributeFieldName, string $attributeFieldValue, string $fieldType = 'score'):void {
		if (null === $attribute = DynamicAttributes::find()->where(['name' => $attributeName])->one()) {
			$attribute = new DynamicAttributes();
			$attribute->createAttribute(['name' => $attributeName, 'category' => 0]);
		}
		if (null === $field = $attribute->getPropertyByName($attributeFieldName)) {
			$field = new DynamicAttributeProperty([
				'attributeId' => $attribute->id,
				'name' => $attributeFieldName,
				'type' => $fieldType
			]);
			$field->id = $attribute->setProperty($field, null);
		}
		RelUsersAttributes::linkModels($user_id, $attribute);
		$attribute->setUserProperty($user_id, $field->id, $attributeFieldValue);
	}

}