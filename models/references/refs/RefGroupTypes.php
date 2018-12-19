<?php
declare(strict_types = 1);

namespace app\models\references\refs;

use app\models\groups\Groups;
use app\models\references\Reference;
use yii\helpers\Html;

/**
 * This is the model class for table "ref_group_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property string $color
 */
class RefGroupTypes extends Reference {
	public $menuCaption = 'Типы групп';
	public $menuIcon = false;

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
			'color' => 'Цвет'
		];
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
		Groups::updateAll(['type' => $toId], ['type' => $fromId]);
		self::deleteAll(['id' => $fromId]);
		self::flushCache();
	}

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array {
		return [
			'id',
			[
				'attribute' => 'name',
				'value' => function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:Html::tag('span', $model->name, [
						'style' => "background: {$model->color}"
					]);
				},
				'format' => 'raw'
			]
		];
	}
}
