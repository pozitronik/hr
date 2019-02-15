<?php
declare(strict_types = 1);

namespace app\modules\references\models\refs;

use app\modules\references\models\Reference;
use app\models\relations\RelUsersAttributesTypes;
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
			[['deleted'], 'integer'],
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
				'value' => function($model) {
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
		return (int)RelUsersAttributesTypes::find()->where(['type' => $this->id])->count();
	}

}
