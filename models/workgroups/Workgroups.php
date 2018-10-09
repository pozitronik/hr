<?php
declare(strict_types = 1);

namespace app\models\workgroups;

use app\models\employees\Employees;
use app\models\relations\EmployeesWorkgroups;
use app\models\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "workgroups".
 *
 * @property int $id
 * @property string $name Название
 * @property string $comment Описание
 * @property int $deleted
 *
 * @property-read Employees[] $employees
 */
class Workgroups extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'workgroups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 512]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'comment' => 'Описание',
			'deleted' => 'Deleted'
		];
	}

	/**
	 * @return EmployeesWorkgroups[]|ActiveQuery
	 */
	public function getEmployees_workgroups() {
		return $this->hasMany(EmployeesWorkgroups::class, ['workgroup_id' => 'id']);
	}

	/**
	 * @return Employees[]|ActiveQuery
	 */
	public function getEmployees() {
		return $this->hasMany(Users::class, ['id' => 'employee_id'])->via('employees_workgroups');
//		echo $x->createCommand()->rawSql;
//		die;
		/*switch ($this->id) {
			case 1:
				return [
					new Users([
						'id' => 2,
						'username' => 'Артемий Лебедев'
					]),
					new Users([
						'id' => 3,
						'username' => 'Юрий Дудь'
					]),
					new Users([
						'id' => 4,
						'username' => 'Гомер Симпсон'
					])
				];
			break;
			case 2:
				return [
					new Users([
						'id' => 5,
						'username' => 'Пабло Эскобар'
					]),
					new Users([
						'id' => 6,
						'username' => 'Свинка Пеппа'
					]),
					new Users([
						'id' => 7,
						'profile_image' => '7.gif',
						'username' => 'Малосольный Пончик'
					])
				];
			break;
			default:
				return [
					new Users([
						'id' => 8,
						'username' => 'Медведь Шатун'
					]),
					new Users([
						'id' => 9,
						'username' => 'Генадий Викторович'
					]),
					new Users([
						'id' => 10,
						'username' => 'Соседка по комнате'
					])
				];
			break;
		}*/

	}
}
