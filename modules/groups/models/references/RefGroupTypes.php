<?php
declare(strict_types = 1);

namespace app\modules\groups\models\references;

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\components\pozitronik\references\models\CustomisableReference;
use app\components\pozitronik\references\ReferencesModule;
use app\components\pozitronik\badgewidget\BadgeWidget;
use Throwable;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * This is the model class for table "ref_group_types".
 *
 * @property int $id
 * @property string $name Название
 * @property int $deleted
 * @property-read int $usedCount Количество объектов, использующих это значение справочника
 * @property Groups $relGroups
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
		self::deleteAll(['id' => $fromId]);
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

	/**
	 * @return Groups|ActiveQuery
	 */
	public function getRelGroups() {
		return $this->hasMany(Groups::class, ['type' => 'id']);
	}

	/**
	 * {@inheritDoc}
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
						]).$model->name:BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => self::colorStyleOptions()
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount',
				'filter' => false,
				'value' => static function($model) {
					/** @var self $model */
					return BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'usedCount',
						'linkScheme' => [GroupsModule::to(['groups/index']), 'GroupsSearch[type]' => 'id'],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw'
			]
		];
	}
}
