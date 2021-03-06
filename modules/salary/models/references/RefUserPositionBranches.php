<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\components\pozitronik\references\models\Reference;
use app\components\pozitronik\references\ReferencesModule;
use app\modules\salary\models\relations\RelRefUserPositionsBranches;
use app\components\pozitronik\badgewidget\BadgeWidget;
use yii\helpers\Html;

/**
 * Справочник веток должностей. Ветка должности - необязательный, не влияющий ни на что атрибут должности.
 */
class RefUserPositionBranches extends Reference {
	public $menuCaption = 'Ветки должностей';
	public $menuIcon = false;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_branches';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['name', 'color', 'textcolor'], 'string', 'max' => 256]
		];
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
						]).$model->name:BadgeWidget::widget([
						'models' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false
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
						'linkScheme' => [ReferencesModule::to(['references/index']), 'class' => 'RefUserPositions', 'RefUserPositions[branchId]' => 'id'],
						'itemsSeparator' => false
					]);
				},
				'format' => 'raw'
			]
		];
	}

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelRefUserPositionsBranches::find()->where(['position_branch_id' => $this->id])->count();
	}
}