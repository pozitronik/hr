<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\references\models\Reference;
use app\modules\references\ReferencesModule;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;

/**
 * Справочник типов должностей. Тип должности -  необязательный, не влияющий ни на что атрибут должности.
 *
 * @property string $color
 */
class RefUserPositionTypes extends Reference {
	public $menuCaption = 'Типы должностей';
	public $menuIcon = false;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_types';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id'], 'integer'],
			[['deleted'], 'boolean'],
			[['name', 'color'], 'string', 'max' => 256]
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
						'data' => $model,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount'
			]

		];
	}
}