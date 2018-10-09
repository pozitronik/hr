<?php

namespace app\models\relations;

use Yii;

/**
 * This is the model class for table "rel_employees_workgroups".
 *
 * @property int $employee_id Сотрудник
 * @property int $workgroup_id Рабочая группа
 * @property int $employee_role_id Роль сотрудника в группе
 */
class EmployeesWorkgroups extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rel_employees_workgroups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['employee_id', 'workgroup_id', 'employee_role_id'], 'required'],
			[['employee_id', 'workgroup_id', 'employee_role_id'], 'integer'],
			[['employee_id', 'workgroup_id'], 'unique', 'targetAttribute' => ['employee_id', 'workgroup_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'employee_id' => 'Сотрудник',
			'workgroup_id' => 'Рабочая группа',
			'employee_role_id' => 'Роль сотрудника в группе',
		];
	}
}
