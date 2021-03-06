<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_select;

use app\components\pozitronik\selectmodelwidget\SelectModelWidget;
use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;

/**
 * Виджет выбора атрибута (чего-либо, не обязательно пользователя - хотя на данный момент подразумевается, что атрибуты есть только у пользователей)
 * Class AttributeSelectWidget
 * @package app\modules\dynamic_attributes\widgets\attribute_select

 */
class AttributeSelectWidget extends SelectModelWidget {
	public $selectModel = DynamicAttributes::class;
	public $jsPrefix = 'DynamicAttributes';


	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AttributeSelectWidgetAssets::register($this->getView());
		$this->ajaxSearchUrl = self::DATA_MODE_AJAX === $this->loadingMode?DynamicAttributesModule::to('ajax/attribute-search'):$this->ajaxSearchUrl;
	}

}
