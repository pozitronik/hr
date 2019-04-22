<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models;

use app\models\core\ActiveRecordExtended;

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
class Vacancy extends ActiveRecordExtended {
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
}
