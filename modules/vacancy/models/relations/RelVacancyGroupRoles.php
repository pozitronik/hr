<?php
declare(strict_types = 1);

namespace app\modules\vacancy\models\relations;

use app\models\core\ActiveRecordExtended;
use app\models\relations\Relations;

/**
 * Модель связи вакансии с ролями. Предполагается, что вакансия принадлежит только одной группе (или вообще не принадлежит группе), но ролей у неё может быть любое количество
 *
 * @property int $id
 * @property int $vacancy_id
 * @property int $role_id
 */
class RelVacancyGroupRoles extends ActiveRecordExtended {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_vacancy_group_roles';
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
			'vacancy_id' => 'Vacancy ID',
			'role_id' => 'Role ID'
		];
	}
}
