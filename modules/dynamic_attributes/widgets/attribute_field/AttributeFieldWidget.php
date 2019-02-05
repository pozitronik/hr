<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use kartik\base\InputWidget;

/**
 * Виджет, рисующий блок одного любого поля пользовательского атрибута
 * Class AttributeFieldWidget
 *
 * @property DynamicAttributeProperty $model
 * @property string $attribute
 * @property bool $readOnly
 * @property bool $showEmpty
 */
class AttributeFieldWidget extends InputWidget {
	public $model;
	public $readOnly = false;
	public $showEmpty = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AttributeFieldWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {


		return $this->readOnly?$this->render('attribute_field_view', [
			'model' => $this->model
		]):$this->render('attribute_field_edit', [
			'model' => $this->model
		]);
	}
}
