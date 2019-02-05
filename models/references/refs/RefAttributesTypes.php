<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use app\models\references\Reference;
use app\models\relations\RelUsersAttributesTypes;

/**
 * This is the model class for table "ref_attributes_types".
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefAttributesTypes extends Reference {
	public $menuCaption = 'Типы атрибутов';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_attributes_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['deleted'], 'integer'],
			[['name', 'color'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted',
			'color' => 'Цвет',
			'usedCount' => 'Использований'
		];
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelUsersAttributesTypes::find()->where(['type' => $this->id])->count();
	}
}
