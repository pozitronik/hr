<?php
declare(strict_types = 1);

namespace app\widgets\attribute_field;

use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use kartik\base\InputWidget;

/**
 * Виджет, рисующий блок одного любого поля ползовательского атрибута
 * Class AttributeFieldWidget
 * @package app\widgets\attribute_field
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
		//if ($this->readOnly && !$this->showEmpty && (null === $this->model->{$this->attribute})) return '';

		return $this->readOnly?$this->render('attribute_field_view', [
			'attribute' => $this->attribute,
			'model' => $this->model
		]):$this->render('attribute_field_edit', [
			'attribute' => $this->attribute,
			'model' => $this->model
		]);
	}
}
