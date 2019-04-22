<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\ActiveRecordExtended;
use app\models\core\StrictInterface;
use app\models\user\CurrentUser;
use app\widgets\alert\AlertModel;

/**
 * This is the model class for table "sys_vacancy".
 *
 * @property int $id
 * @property int $vacancy_id Внешний ID вакансии
 * @property int $ticket_id ID заявки на подбор
 * @property int $status Статус вакансии
 * @property int $group Группа
 * @property string $name Опциональное название вакансии
 * @property int $location Локация
 * @property int $recruiter Рекрутер
 * @property int $employer Нанимающий руководитель
 * @property int $position Должность
 * @property int $role Назначение/роль
 * @property int $teamlead teamlead
 * @property string $create_date Дата заведения вакансии
 * @property string $close_date Дата закрытия вакансии
 * @property string $estimated_close_date Дата ожидаемого закрытия вакансии
 * @property int $daddy Автор вакансии
 * @preoprty bool $deleted
 */
class Vacancy extends ActiveRecordExtended implements StrictInterface {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_vacancy';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['vacancy_id', 'ticket_id', 'status', 'group', 'location', 'recruiter', 'employer', 'position', 'role', 'teamlead', 'daddy'], 'integer'],
			[['group', 'position', 'create_date', 'daddy'], 'required'],
			[['create_date', 'close_date', 'estimated_close_date'], 'safe'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'vacancy_id' => 'Внешний ID вакансии',
			'ticket_id' => 'ID заявки на подбор',
			'status' => 'Статус вакансии',
			'group' => 'Группа',
			'name' => 'Опциональное название вакансии',
			'location' => 'Локация',
			'recruiter' => 'Рекрутер',
			'employer' => 'Нанимающий руководитель',
			'position' => 'Должность',
			'role' => 'Назначение/роль',
			'teamlead' => 'teamlead',
			'create_date' => 'Дата заведения вакансии',
			'close_date' => 'Дата закрытия вакансии',
			'estimated_close_date' => 'Дата ожидаемого закрытия вакансии',
			'daddy' => 'Автор вакансии'
		];
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function createModel(?array $paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate()
			]);
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					$this->refresh();
					AlertModel::SuccessNotify();
					return true;
				}
				AlertModel::ErrorsNotify($this->errors);
			}
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param array|null $paramsArray
	 * @return bool
	 */
	public function updateModel(?array $paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				$this->refresh();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}
}
