<?php
declare(strict_types = 1);

namespace app\modules\targets\models\references;

use app\modules\references\models\CustomisableReference;
use app\modules\references\ReferencesModule;
use app\widgets\badge\BadgeWidget;
use kartik\helpers\Html;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $name
 * @property int|null $parent -- id родительского типа цели, null если высший
 *
 * @property RefTargetsTypes|ActiveQuery $relParent
 * @property RefTargetsTypes|ActiveQuery $relChild
 * @property-read bool $isFinal
 */
class RefTargetsTypes extends CustomisableReference {
	public $menuCaption = 'Типы целей';
	public $menuIcon = false;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_targets_types';
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id', 'usedCount', 'parent'], 'integer'],
			[['deleted'], 'boolean'],
			[['name', 'color', 'textcolor'], 'string', 'max' => 256]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Удалёно',
			'usedCount' => 'Использований',
			'color' => 'Цвет фона',
			'textcolor' => 'Цвет текста',
			'parent' => 'Вышестоящий тип',
			'isFinal' => 'Финальный тип'
		];
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
				'attribute' => 'isFinal',
				'format' => 'boolean',
				'options' => [
					'style' => 'width:36px;'
				]
			],
			[
				'attribute' => 'parent',
				'options' => [
					'style' => 'width:36px;'
				],
				'value' => static function($model) {
					/** @var self $model */
					return BadgeWidget::widget([
						'models' => $model->relParent,
						'attribute' => 'name',
						'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
					]);
				},
				'format' => 'raw',
				'filter' => false
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
						"optionsMap" => static function() {
							return self::colorStyleOptions();
						}
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
						'linkScheme' => false,//todo
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

	/**
	 * @return RefTargetsTypes|ActiveQuery
	 */
	public function getRelParent() {
		return $this->hasOne(static::class, ['id' => 'parent']);
	}

	/**
	 * @return RefTargetsTypes|ActiveQuery
	 */
	public function getRelChild() {
		return $this->hasOne(static::class, ['parent' => 'id']);
	}

	/**
	 * Является ли тип цели финальным.
	 * Финальный тип не может иметь нижестоящие цели, но имеет атрибуты сроков и может зеркалироваться (т.е. это "цель" в понятиях бизнеса)
	 * @return bool
	 */
	public function getIsFinal():bool {
		return (null !== $this->parent && null === $this->relChild);
	}

	/**
	 * Вернёт id типа, вычисленного, как финальный
	 * @param bool $throwOnNull
	 * @return static|null
	 * @throws InvalidConfigException
	 */
	public static function final(bool $throwOnNull = true):?self {
		if ((null === $result =  static::find()->joinWith('relChild child')->where(['not', ['ref_targets_types.parent' => null]])->andWhere(['child.id' => null])->one()) && $throwOnNull) {
			throw new InvalidConfigException('Не могу найти финальный тип задачи в справочнике типов целей');
		}
		return $result;
	}

}
