<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\relations;

use pozitronik\core\traits\Relations;
use app\modules\history\models\HistoryEventInterface;
use app\modules\users\models\references\RefUserRoles;
use app\modules\vacancy\models\Vacancy;
use yii\db\ActiveRecord;

/**
 * Модель связи вакансии с ролями. Предполагается, что вакансия принадлежит только одной группе (или вообще не принадлежит группе), но ролей у неё может быть любое количество
 *
 * @property int $id
 * @property int $vacancy_id
 * @property int $role_id
 */
class RelVacancyGroupRoles extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_vacancy_group_roles';
	}

	/**
	 * @return array
	 */
	public function historyRules():array {
		return [
			'eventConfig' => [
				'eventLabels' => [
					HistoryEventInterface::EVENT_CREATED => 'Добавление роли вакансии',
					HistoryEventInterface::EVENT_CHANGED => 'Изменение роли вакансии',
					HistoryEventInterface::EVENT_DELETED => 'Удаление роли вакансии'
				]
			],
			'attributes' => [
				'role_id' => [RefUserRoles::class => 'name'],
				'vacancy_id' => [Vacancy::class => 'id']
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['vacancy_id', 'role_id'], 'required'],
			[['vacancy_id', 'role_id'], 'integer'],
			[['vacancy_id', 'role_id'], 'unique', 'targetAttribute' => ['vacancy_id', 'role_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'vacancy_id' => 'Вакансия',
			'role_id' => 'Роль'
		];
	}
}
