<?php
declare(strict_types = 1);

namespace app\modules\groups\models\references;

use app\modules\references\models\Reference;
use app\models\relations\RelGroupsGroups;
use app\modules\references\ReferencesModule;
use app\widgets\badge\BadgeWidget;
use Throwable;
use yii\helpers\Html;

/**
 * This is the model class for table "ref_group_relation_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property string $color
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefGroupRelationTypes extends Reference {
	public $menuCaption = 'Типы соединений групп';
	public $menuIcon = false;

	protected $_dataAttributes = ['color'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_group_relation_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['id'], 'integer'],
			[['deleted'], 'boolean'],
			[['name', 'color'], 'string', 'max' => 256],
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

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws Throwable
	 */
	public static function merge(int $fromId, int $toId):void {
		RelGroupsGroups::updateAll(['relation' => $toId], ['relation' => $fromId]);
		self::deleteAllEx(['id' => $fromId]);
		self::flushCache();
	}


	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelGroupsGroups::find()->where(['relation' => $this->id])->count();
	}

}
