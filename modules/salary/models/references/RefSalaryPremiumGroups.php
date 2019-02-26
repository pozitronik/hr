<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\references\models\Reference;
use yii\helpers\Html;

/**
 * Справочник премиальных групп. Премиальная группа применяется, как модификатор при указании зарплатной вилки
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 */
class RefSalaryPremiumGroups extends Reference {
	public $menuCaption = 'Премиальные группы';
	public $menuIcon = false;

	protected $_dataAttributes = ['color'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_salary_premium_group';
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

}
