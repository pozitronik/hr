<?php
declare(strict_types = 1);

namespace app\models\references\refs;


use app\models\references\Reference;

/**
 * This is the model class for table "ref_group_types".
 *
 * @property int $id
 * @property string $name Название
 * @property string $value Описание
 * @property int $deleted
 */
class RefGroupTypes extends Reference {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_group_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'value'], 'required'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['value'], 'string', 'max' => 512]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'value' => 'Описание',
			'deleted' => 'Deleted'
		];
	}
}
