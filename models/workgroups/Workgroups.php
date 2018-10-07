<?php
declare(strict_types = 1);

namespace app\models\workgroups;

use app\models\employees\Employees;
use app\models\users\Users;
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
	 * @return Employees[]
	 */
	public function getEmployees():array {
		switch ($this->id) {
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
					]),
				];
			break;
			case 2:
				return [
					new Users([
						'id' => 5,
						'username' => 'Артемий Лебедев'
					]),
					new Users([
						'id' => 6,
						'username' => 'Юрий Дудь'
					]),
					new Users([
						'id' => 7,
						'username' => 'Гомер Симпсон'
					]),
				];
			break;
			default:
				return [
					new Users([
						'id' => 8,
						'username' => 'Артемий Лебедев'
					]),
					new Users([
						'id' => 9,
						'username' => 'Юрий Дудь'
					]),
					new Users([
						'id' => 10,
						'username' => 'Гомер Симпсон'
					]),
				];
			break;
		}

	}
}
