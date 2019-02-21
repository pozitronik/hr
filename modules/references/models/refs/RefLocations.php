<?php
declare(strict_types = 1);

namespace app\modules\references\models\refs;

use app\modules\references\models\Reference;

/**
 * This is the model class for table "ref_locations".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 *
 * @property string $color
 *
 */
class RefLocations extends Reference {
	public $menuCaption = 'Локации';
	public $menuIcon = false;

	protected $_dataAttributes = ['color'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_locations';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['color'], 'safe']
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
}
