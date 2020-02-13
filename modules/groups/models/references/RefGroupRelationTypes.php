<?php
declare(strict_types = 1);

namespace app\modules\groups\models\references;

use pozitronik\references\models\CustomisableReference;
use app\models\relations\RelGroupsGroups;
use Throwable;

/**
 * This is the model class for table "ref_group_relation_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property string $color
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefGroupRelationTypes extends CustomisableReference {
	public $menuCaption = 'Типы соединений групп';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_group_relation_types';
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws Throwable
	 */
	public static function merge(int $fromId, int $toId):void {
		RelGroupsGroups::updateAll(['relation' => $toId], ['relation' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelGroupsGroups::find()->where(['relation' => $this->id])->count();
	}

}
