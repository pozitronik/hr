<?php
declare(strict_types = 1);

namespace app\modules\groups\models\references;

use app\modules\groups\models\Groups;
use app\modules\references\models\CustomisableReference;
use Throwable;

/**
 * This is the model class for table "ref_group_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefGroupTypes extends CustomisableReference {
	public $menuCaption = 'Типы групп';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_group_types';
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws Throwable
	 */
	public static function merge(int $fromId, int $toId):void {
		Groups::updateAll(['type' => $toId], ['type' => $fromId]);
		self::deleteAllEx(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)Groups::find()->where(['type' => $this->id])->count();
	}

	/**
	 * Возващает массив всех типов групп в скоупе пользователя в формате
	 * [
	 *    'id' => 'typeName'
	 * ]
	 * @param int[] $scope -- массив айдишников групп скоупа (может быть уже известен)
	 * @return array
	 */
	public static function getGroupsTypesScope(array $scope = []):array {
		return self::find()->select(['id', 'name'])->distinct()->where(['id' => $scope])->asArray()->all();
	}
}
