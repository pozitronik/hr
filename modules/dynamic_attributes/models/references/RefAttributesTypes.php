<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\references;

use app\modules\references\models\Reference;
use app\models\relations\RelUsersAttributesTypes;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;

/**
 * This is the model class for table "ref_attributes_types".
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefAttributesTypes extends Reference {
	public $menuCaption = 'Типы отношений атрибутов';
	public $menuIcon = false;

	protected $_dataAttributes = ['color'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_attributes_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name'], 'required'],
			[['deleted'], 'boolean'],
			[['name', 'color'], 'string', 'max' => 255]
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
						'data' => [$model],
						'attribute' => 'name',
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

	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelUsersAttributesTypes::find()->where(['type' => $this->id])->count();
	}

}
