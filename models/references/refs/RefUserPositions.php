<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use app\models\references\Reference;
use app\models\users\Users;

/**
 * This is the model class for table "ref_user_positions".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 */
class RefUserPositions extends Reference {
	public $menuCaption = 'Должности';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_user_positions';
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

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		Users::updateAll(['position' => $toId], ['position' => $fromId]);
		self::deleteAll(['id' => $fromId]);
	}
}
