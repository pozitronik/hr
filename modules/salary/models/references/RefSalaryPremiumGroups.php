<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\references\models\Reference;
use app\modules\references\ReferencesModule;
use app\widgets\badge\BadgeWidget;
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
