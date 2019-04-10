<?php
declare(strict_types = 1);

namespace app\modules\groups\models\references;

use app\modules\references\models\Reference;
use app\models\relations\RelGroupsGroups;
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
			[['id', 'deleted'], 'integer'],
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
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array {
		return [
			[
				'attribute' => 'id',
				'options' => [
					'style' => 'width:36px;'
				]
			],
			[
				'attribute' => 'name',
				'value' => static function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:Html::tag('span', Html::a($model->name, ['update', 'class' => $model->formName(), 'id' => $model->id]), [
						'style' => "background: {$model->color}"
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount'
			]

		];
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelGroupsGroups::find()->where(['relation' => $this->id])->count();
	}

}
