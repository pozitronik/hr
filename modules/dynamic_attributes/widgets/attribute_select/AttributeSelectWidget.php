<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_select;

use app\helpers\ArrayHelper;
use app\models\core\SelectionWidgetInterface;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use kartik\base\InputWidget;
use yii\db\ActiveRecord;

/**
 * Виддет выбора атрибута (чего-либо, не обязательно пользователя - хотя на данный момент подразумевается, что атрибуты есть только у пользователей)
 * Class AttributeSelectWidget
 * @package app\modules\dynamic_attributes\widgets\attribute_select
 *
 * @property ActiveRecord|null $model Модель, с которой будет ассоциироваться
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Данные, исключаемые из списка
 * @property boolean $multiple
 * @property string|array $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 */
class AttributeSelectWidget extends InputWidget implements SelectionWidgetInterface {
	public $mode = self::MODE_FIELD;
	public $notData;
	public $multiple = false;
	public $groupByType = true;
	public $formAction = '';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AttributeSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {

		$data = ArrayHelper::map($this->model->isNewRecord?DynamicAttributes::find()->active()->all():DynamicAttributes::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->model->{$this->attribute}, 'id')])->all(), 'id', 'name');

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('attribute_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple
				]);
			break;
			case self::MODE_FORM:
				return $this->render('attribute_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'formAction' => $this->formAction
				]);
			break;
		}
	}
}
