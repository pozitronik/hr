<?php
declare(strict_types = 1);

namespace app\models\references\refs;


use app\models\references\Reference;

/**
 * This is the model class for table "ref_user_roles".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 */
class RefUserRoles extends Reference {
	public $menuCaption = 'Роли пользователей внутри групп';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_roles';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 256]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted'
		];
	}
}
