<?php
declare(strict_types = 1);

namespace app\modules\targets\models\references;


use app\modules\references\models\Reference;

/**
 * @property int $id
 * @property string $name
 */
class RefTargetsResults extends Reference {
	public $menuCaption = 'Типы оценок целей';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_targets_results';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['id', 'usedCount'], 'integer'],
			[['deleted'], 'boolean']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'usedCount' => 'Использований'
		];
	}
}
