<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\references\Reference;

/**
 * This is the model class for table "ref_group_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property-read string $color
 */
class RefGroupTypes extends Reference {
	public $menuCaption = 'Типы групп';
	public $menuIcon = false;
	public const COLORS = [
		1 => 'rgb(100,250,100)',
		2 => 'rgb(255,10,10)',
		3 => 'rgb(70,90,200)',
		4 => 'rgb(10,10,10)',
		5 => 'rgb(100,150,250)',
		6 => 'rgb(50,250,10)'
	];

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
			[['name'], 'required'],
			[['deleted'], 'integer'],
			[['name', 'color'], 'string', 'max' => 256]
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
	 * @return string
	 * @throws \Throwable
	 */
	public function getColor():string {
		return ArrayHelper::getValue(self::COLORS, $this->id, 'rgb(255,10,255)');
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		Groups::updateAll(['type' => $toId], ['type' => $fromId]);
		self::deleteAll(['id' => $fromId]);
	}
}
